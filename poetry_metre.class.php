<?php

/**
 * Arabic Poetry Metre
 *
 * @author	B. Nabil
 * @link	http://misht.nbyl.me
 * @version		0.1
 * @date	2012	
 */

class PoetryMetre {

	private $data = array(
		'حروف العلة' => array('ا', 'ي', 'و'), //vowels
		'محركات' =>  array('َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ'),
		'تنوين' => array('ً', 'ٌ', 'ٍ'),
		'شدة' => 'ّ',
		'سكون' => 'ْ',
		'الحروف' => array('إ', 'ئ', 'ؤ', 'ء', 'ة', 'أ', 'ا', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي'),
		'حروف شمسية' => array('ت' ,'ث' , 'د', 'ذ', 'ر', 'ز' ,'س' , 'ش' ,'ص' , 'ض', 'ط' ,'ظ' , 'ن' , 'ل'),
		'خاص' => array(
					'ال%' => 0, // ال في بدية الكلمة
					'ال' => 1, //ال في بداية البيت
					'وا ' => 0, // نهاية كلمة داخلية
					'اً' => 0,
					//'وا' => 0, //نهاية البيت
				),
		'البحور' => array('', 'الطويل', 'المديد', 'البسيط', 'الوافر', 'الكامل', 'الهزج', 'الرجز', 'الرمل', 'السريع', 'المنسرح', 'الخفيف', 'المضارع', 'المقتضب', 'المجتث', 'المتقارب', 'المحدث'),
		'شجرة البحور' => array(
			1 => array(),
			2 => array(),
			3 => array(),
			'المتقارب'=>array(
				'32' => array('22', '21', '31'),
			),
		),
		'تفعيلات' => array(
			1 => array(

			),
			2 => array(

			),
			3 => array(

			),
		),
	);





	private $input;
	public $output = array(
		"توجيه" => array(
			'حالة' => 'إشعار',
			'رسالة' => "البرنامج لايزال تجريبيا في أولى مراحله يرجى مراسلتنا في أي اقتراح أو خلل",
			),

	);


	/**
	 * Constrcucter - Return final results
	 *
	 * This function may return the output if she get params
	 *
	 */
	public function __construct(){

	}

	/**
	 * Output - Return final results
	 *
	 * This function will be called at the moment to get the final result
	 *
	 * @param	array	of verses (string)
	 * @param	bool	return details - not operational
	 * @return	array
	 */
	public function output($input, $specifics=false){

		$this->input = $input;
		foreach($this->input as $key => $verse){
			if($this->prepare($key, $verse)){
				$this->output[$key] = array(
					'letters' => array(), 
					'analyse' => array(
						1 => "",
						2 => "",
						3 => "",
						4 => "",
						5 => "",
					),
					'code' => array(),
					'code2' => array(),
					'code3' => array(),
					'توجيه' => array('البحر'=>''),
				);
				$this->compute_bit($key, $verse);
				$this->compute_part($key, $verse);
				$this->compute_metre($key, $verse);
				$this->evaluate_the_sea_of($key, $verse);
				$this->analysis($key, $verse);
			}
		}
		return $this->output;
	}

	/**
	 * Prepare phase
	 *
	 * This function will control and prepare the verse
	 *
	 * @param	int	the number of the verse
	 * @param	string	the verse
	 * @return	
	 */
	public function prepare($key, $verse, $options = array('spec'=>true)){

		if(preg_match('/\p{Arabic}/u', $verse)){
			$verse .= "    ";

			//SPECIAL
			if($options['spec']){

			}

			return true;
		}else
			$this->output['توجيه'] = array(
				'حالة' => 'خلل',
				'رسالة' => "البرنامج يعتمد على الحروف العربية"
			);
		return false;
	}

	/**
	 * Phase x - Process 
	 *
	 * This function will be used ...
	 *
	 * @param	int	the number of the verse
	 * @param	string	the verse
	 * @return	
	 */
	private function compute_bit($key, $verse){
			


		$this->output[$key]["letters"] = preg_split('//u', $verse, -1, PREG_SPLIT_NO_EMPTY);

	
		$i=0;

		while($i<count($this->output[$key]["letters"])){
	
	
			if(in_array($this->output[$key]["letters"][$i], $this->data["الحروف"])){ // si lettre
			
				if(isset($this->output[$key]["letters"][$i+1])){		// si lettre suivante existe
				
					if($this->output[$key]["letters"][$i+1] == $this->data["سكون"]){ // si la lettre suivante est soukoune
					
						array_push($this->output[$key]["code"], 0);
						$i ++;
					}

					// si la lettre suivante est muharikattes ou voyelles
					// la duplication de voyelles ? يا وا وو اا ??
					elseif(in_array($this->output[$key]["letters"][$i+1], $this->data["محركات"])){
					
						if(in_array($this->output[$key]["letters"][$i+1], $this->data["تنوين"])){
							array_push($this->output[$key]["code"], 1);
							array_push($this->output[$key]["code"], 0);
						}
					
						elseif(isset($this->output[$key]["letters"][$i+2]) and $this->output[$key]["letters"][$i+2] == $this->data["شدة"]){
						
							array_push($this->output[$key]["code"], 0);
							array_push($this->output[$key]["code"], 1);
							$i ++;
						
						}else{
							array_push($this->output[$key]["code"], 1);
						}
					
						$i ++;
					}
				
					elseif(in_array($this->output[$key]["letters"][$i+1], $this->data["حروف العلة"])){ 
						array_push($this->output[$key]["code"], 1);
					}
				
					// si la lettre est suivante est chidda puis haraka
					elseif($this->output[$key]["letters"][$i+1] == $this->data["شدة"]){
					
					
						//passer la 7araka qui suit si elle existe
						if(isset($this->output[$key]["letters"][$i+2]) and in_array($this->output[$key]["letters"][$i+2], $this->data["محركات"])){
							$i ++;
						}
					
						array_push($this->output[$key]["code"], 0);
						array_push($this->output[$key]["code"], 1);
						$i ++;
					}
				
					else
						array_push($this->output[$key]["code"], 0);	
				}
			}
		
			/*debug*/
			if(in_array($this->output[$key]["letters"][$i], $this->data["محركات"])){ // si voyelles simple

			}

		
			$i++;
		}
	

		// Al9afia ?? pas tjrs ?
		if(end($this->output[$key]["code"])==1){
			array_push($this->output[$key]["code"], 0);
		}

	}
	
	/**
	 * Phase x - Process 
	 *
	 * This function will be used ...
	 *
	 * @param	int	the number of the verse
	 * @param	string	the verse
	 * @return	
	 */
	public function compute_part($key, $verse){
		$i = 0;
	
	 	while($i<count($this->output[$key]["code"])){
		
			if(isset($this->output[$key]["code"][$i+1]) and
				$this->output[$key]["code"][$i]==1 and $this->output[$key]["code"][$i+1]==0){
				
				if(isset($this->output[$key]["code"][$i+2]) and isset($this->output[$key]["code"][$i+3]) and
					$this->output[$key]["code"][$i+2]==1 and $this->output[$key]["code"][$i+3]==0){
				
					array_push($this->output[$key]["code2"], 4);
					$i += 4;
				}else{
					array_push($this->output[$key]["code2"], 2);
					$i += 2;
				}
			}
		
			elseif(isset($this->output[$key]["code"][$i+1]) and isset($this->output[$key]["code"][$i+2]) and 
					$this->output[$key]["code"][$i]==1 and $this->output[$key]["code"][$i+1]==1 and $this->output[$key]["code"][$i+2]==0){
					
				array_push($this->output[$key]["code2"], 3);
				$i += 3;
			}
		
			elseif(isset($this->output[$key]["code"][$i+1]) and isset($this->output[$key]["code"][$i+2]) and isset($this->output[$key]["code"][$i+3]) and
					$this->output[$key]["code"][$i]==1 and $this->output[$key]["code"][$i+1]==1 and $this->output[$key]["code"][$i+2]==1){
					
				array_push($this->output[$key]["code2"], 1);
				$i += 1;	
			}else
				//$answer["note"] = "خلل في بنية البيت";
				$i ++;
			
			
			//echo $i." - ";
		}
	}

	/**
	 * Phase x - Process 
	 *
	 * This function will be used ...
	 *
	 * @param	int	the number of the verse
	 * @param	string	the verse
	 * @return	
	 */
	public function compute_metre($key, $verse){
		$i = 0;
		//exit;
		//print_r($this->output[$key]["code2"]);
	 	while($i<count($this->output[$key]["code2"])){
			//echo $this->output[$key]["code2"][$i];

			if(isset($this->output[$key]["code2"][$i+1])){

				if($this->output[$key]["code2"][$i]==3 and $this->output[$key]["code2"][$i+1]==2){
					array_push($this->output[$key]["code3"], "فَعولُن");
					$i += 2;
				}
				elseif($this->output[$key]["code2"][$i]==3 and $this->output[$key]["code2"][$i+1]==1){
					array_push($this->output[$key]["code3"], "فَعولُ");
					$i += 2;
				}
				elseif($this->output[$key]["code2"][$i]==3 and $this->output[$key]["code2"][$i+1]==3){
					array_push($this->output[$key]["code3"], "فَعِلُن");
					$i += 2;
				}
				elseif($this->output[$key]["code2"][$i]==2 and $this->output[$key]["code2"][$i+1]==2){
					array_push($this->output[$key]["code3"], "فَعْلُن");
					$i += 2;
				}else
					$i++;

			}elseif($this->output[$key]["code2"][$i]==3){
				array_push($this->output[$key]["code3"], "فَعو");
				$i += 2;
			}

			else
				$i ++;
		}
	}

	/**
	 * Phase x - Process 
	 *
	 * This function will evaluate the sea of the given verse
	 *
	 * @param	int	the number of the verse
	 * @param	string	the verse
	 * @return	
	 */
	public function evaluate_the_sea_of($key, $verse = null){

		// TODO an interpreted language for the specifics of the sea's metree.
		//$this->data['تفعيلات'][][] [][] [][] [][] = ;

		// Sea 15 AlMoutaqarib
		$this->data['تفعيلات'][3][1] [3][1] [3][1] [3][2] = 15;
		$this->data['تفعيلات'][3][2] [3][1] [3][1] [3][2] = 15;
		$this->data['تفعيلات'][3][1] [3][2] [3][1] [3][2] = 15;
		$this->data['تفعيلات'][3][2] [3][2] [3][1] [3][2] = 15;
		//-
		$this->data['تفعيلات'][3][1] [3][1] [3][2] [3][2] = 15;
		$this->data['تفعيلات'][3][2] [3][1] [3][2] [3][2] = 15;
		$this->data['تفعيلات'][3][1] [3][2] [3][2] [3][2] = 15;
		$this->data['تفعيلات'][3][2] [3][2] [3][2] [3][2] = 15;

		//sea 15.5 Madjou AlMoutaqarib
		//$this->data['تفعيلات'][3][1] [3][2] = 15.5;
		//$this->data['تفعيلات'][3][2] [3][2] = 15.5;
		
		// Sea 5 Alkamil
		$this->data['تفعيلات'][4][3] [4][3] [4][3] [4][3] = 5;
		$this->data['تفعيلات'][4][3] [4][3] [4][3] [4][2] = 5;
		


		$current = $this->data['تفعيلات'];

		foreach($this->output[$key]["code2"] as $num){

			if(isset($current[$num])){
				if(is_numeric($current[$num])){
					$this->output[$key]["توجيه"]["البحر"] = $this->data['البحور'][$current[$num]];

				}
				$current = $current[$num];
			}
		}

		if($this->output[$key]["توجيه"]["البحر"] == ""){
			$this->output[$key]["توجيه"]["البحر"] = "لم يتم التعرف على بحر هذا الشطر.";
		}

	}


	/**
	 * Phase x - Process 
	 *
	 * This function will evaluate the sea of all verses
	 *
	 * @return	
	 */
	public function evaluate_the_sea(){


	}

	/**
	 * Phase x - Process 
	 *
	 * This function will be used ...
	 *
	 * @param	int	the number of the verse
	 * @param	string	the verse
	 * @return	
	 */
	private function analysis($key, $verse){

		foreach($this->output[$key]["code"] as $l){
			$this->output[$key]["analyse"][1] .= $l."|";
		}
		//$this->output[$key]["analyse"][1] .= "|";
	
		foreach($this->output[$key]["code2"] as $l){
			$this->output[$key]["analyse"][2] .= $l."|";
		}

		foreach($this->output[$key]["code3"] as $l){
			$this->output[$key]["analyse"][3] .= $l." | ";
		}
		
		
		if($this->output[$key]["توجيه"]["البحر"] != "لم يتم التعرف على بحر هذا الشطر."){		
			$this->output[$key]["analyse"][4] = "يرجح أن هذا الشطر من البحر <b>".$this->output[$key]["توجيه"]["البحر"]."</b>";
		}else 
			$this->output[$key]["analyse"][4] = $this->output[$key]["توجيه"]["البحر"];
	}

	/**
	 * Debug 
	 *
	 *
	 * @return	
	 */
	public function debug(){


	}

}




