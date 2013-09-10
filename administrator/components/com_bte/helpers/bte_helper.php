<?php

class BodyTextExtractor{
	public function extractFromUrl($sz_Url){
		$string = file_get_contents($sz_Url);
		$body_text = $this->extractFromString($string);
		return $body_text;
	}

	public function extractFromString($string){

		$body_text = "";
		//Parse data
		//Remove all un-useful info and tag
		$o_HtmlParser = new HtmlParser();
		$a_DataParser = $o_HtmlParser->parser($string);
		if(!$a_DataParser) return 'Cannot read your data !';

		$binary_token = array();
		$endcode = array();
		$token = array();

		/* do smart binary array */
		$i = 0;
		$j = 0;
		$k = 0;
		foreach ($a_DataParser['tokenized_data'] as $index => $sz_tokenizedData) {
			if($this->isTag($a_DataParser, $index)) {
				$binary_token[$i] = 1;
			} elseif ($this->isTagClose($a_DataParser, $index)) {
				$binary_token[$i] = 1;
			} else {
				$binary_token[$i] = -1;
			}
				
			$token[$i] = $sz_tokenizedData;
			$i++;
		}

		$endcode[$j] = 0;
		for($k =0; $k < $i; $k++) {
			$x = $binary_token[$k];
			if( abs($x + $endcode[$j]) < abs($endcode[$j])) {
				$j++;
			}

			if(!isset($endcode[$j])) {
				$endcode[$j] = 0;
			}
			$endcode[$j]= $endcode[$j]+$x;
		}

		/* Extract body text */
		$i_max = 0;
		$j_max = count($endcode) - 1;
		$max = 0;
		for($i = 0; $i < $j_max - 1 ; $i++) {
			if ($endcode[$i] > 0) {
				continue;
			}
				
			for($j = $j_max; $j > $i; $j--) {
				if ( $endcode[$j] > 0) {
					continue;
				}

				$S = $this->tagToI($endcode, $i) + $this->tagAfterJ($endcode, $j) + $this->textBetweenIJ($endcode, $i, $j);
				if($S > $max) {
					$max = $S;
					$i_max = $i;
					$j_max = $j;
				}
			}
		}

		/* Calculate start and end point */
		$start = 0;
		$end = 0;
		for ($i=0; $i < $i_max; $i++) {
			$start += abs($endcode[$i]);
		}

		for ($i=0; $i < $j_max; $i++) {
			$end += abs($endcode[$i]);
		}

		/* Calculate body text */
		for($i = $start - 1; $i <= $end -1; $i++) {
			//Uncomment for get text without html tag
			//if (isset($token[$i]) && $binary_token[$i] < 0)
			if (isset($token[$i]) && $binary_token[$i] < 0)
				$body_text .=  $token[$i];
		}

		return $body_text;
	}

	protected function isTag($a_DataExtraction, $i_index) {
		if($a_DataExtraction['binary_token'][$i_index] == HtmlParser::TAG_DATA) return true;
		return false;
	}

	protected function isTagClose($a_DataExtraction, $i_index) {
		if($a_DataExtraction['binary_token'][$i_index] == HtmlParser::TAG_CLOSE) return true;
		return false;
	}

	protected function tagToI($endcode, $i) {
		$total = 0;
		for($index = 0; $index < $i; $index++) {
			if($endcode[$index] > 0) {
				$total += $endcode[$index];
			}
		}
		return $total;
	}

	protected function tagAfterJ($endcode, $j) {
		$total = 0;
		for($index = $j+1; $index < count($endcode); $index++) {
			if($endcode[$index] > 0) {
				$total += $endcode[$index];
			}
		}
		return $total;
	}

	protected function textBetweenIJ($endcode, $i, $j) {
		$total = 0;
		for($index = $i; $index <= $j; $index++) {
			if($endcode[$index] < 0) {
				$total += $endcode[$index];
			}
		}
		return abs($total);
	}

}

class HtmlParser{

	const TAG_DATA = 'is_tag';
	const TEXT_DATA = 'is_text';
	const TAG_CLOSE = 'tag_close';

	public function parser($string) {

		$a_DataExtraction = array();
		$string = $this->removeRedundantData($string);

		/* Extract data into array */
		$sz_Regx = '/^<[\w]+[^>]*>/s';
		$sz_RegexStartTag = '/<[\w]+[^>]*>/s';
		$sz_RegexCloseTag = '/^<\/[\w]+>/s';
		$sz_RegexIncludeCloseTag = '/<\/[a-zA-Z]+>/s';
		$i = 0;
		while ($string != ''){
			if(preg_match($sz_RegexCloseTag, $string, $matches)) {
				$string = substr($string, strlen($matches[0]));
				$a_DataExtraction['tokenized_data'][] = $matches[0];
				$a_DataExtraction['binary_token'][] = self::TAG_CLOSE;
			}

			elseif(preg_match($sz_Regx, $string, $matches)){
				$a_DataExtraction['tokenized_data'][] = $matches[0];
				$a_DataExtraction['binary_token'][] = self::TAG_DATA;
				$string = substr($string, strlen($matches[0]));
			} else {
				$i_matchPos = 0;
				$i_remainPos = strpos($string, ' ');
				if(preg_match($sz_RegexIncludeCloseTag, $string, $matches)) {
					$i_matchPos = strpos($string, $matches[0]);
					$sz_TextMatch = substr($string, 0, $i_matchPos);
				}

				if(preg_match($sz_RegexStartTag, $string, $matches)) {
					if(strpos($string, $matches[0]) < $i_matchPos){
						$i_matchPos = strpos($string, $matches[0]);
						$sz_TextMatch = substr($string, 0, $i_matchPos);
					}
				}

				if($i_matchPos > 0 && ($i_matchPos < $i_remainPos || $i_remainPos === false)){
					$sz_TokenizedData = $sz_TextMatch;
					$i_remainPos = $i_matchPos;
				} else {
					$sz_TokenizedData = substr($string, 0, $i_remainPos + 1);
				}

				foreach (explode(PHP_EOL, $sz_TokenizedData) as $sz_Token) {
					$a_DataExtraction['tokenized_data'][] = $sz_Token;
					$a_DataExtraction['binary_token'][] = self::TEXT_DATA;
				}
				$string = substr($string, $i_remainPos);
			}
			$string = trim($string);
		}
		return $a_DataExtraction;
	}



	public function removeRedundantData($string) {
		$string = trim($string);
		$a_TagNoContent = array(
				'/\<[^(\/\>)]+?\/\>/s',
				'/<select.*?<\/select>/si',
				'/<button.*?<\/button>/si',
				'/<marquee.*?<\/marquee>/si',
				'/<iframe.*?<\/iframe>/si',
				'/<style.*?<\/style>/si',
				'/<\!--.*?-->/si',
				'/<script.*?<\/script>/si'
		);

		if($string != ''){
			/* Replace all content before and after "Body" tag */
			$sz_Regx = '/<body.*?<\/body>/si';
			if(preg_match($sz_Regx, $string, $matches)){
				$string = $matches[0];
			}

			/* Replace all tags without content */
			if($string != ''){
				$string = preg_replace($a_TagNoContent, '', $string);
			}
		}
		return trim($string);
	}
}