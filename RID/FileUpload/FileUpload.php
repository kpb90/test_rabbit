<?php
	namespace RID\FileUpload;
	use RID\Logger\Logger;
		
	class FileUpload {

		function uploadSingleFileNew2($folder, $url_path) {
			$logo = '';
			if(isset($_FILES['uploadfile']) && $_FILES['uploadfile']['tmp_name'] != '' && $_FILES['uploadfile']['size'] != 0) {
				$file_upload = true;
				$real_name = $_FILES['uploadfile']['name'];
				$tmp_name = basename( $_FILES['uploadfile']['tmp_name']);
		
				$extension_file_name = end(explode(".", $real_name));
				$filename = str_replace ('.'.$extension_file_name,'',$real_name);
		
				$filename = get_translit_singleline(iconv('utf-8','windows-1251',$filename));
		
				$filename_700 = '700__'.$filename.'.'.$extension_file_name;
				$filename_210 = '210__'.$filename.'.'.$extension_file_name;
				$filename .='.'.$extension_file_name;
				$upload_name = $folder . $tmp_name;
				$this->addToLog($filename_210);
				$correct_name_700 = $this->getImageName($folder, $filename_700);
				$correct_name_210 = $this->getImageName($folder, $filename_210);
				$correct_name = $this->getImageName($folder, $filename);
		
				if(rename($_FILES['uploadfile']['tmp_name'], $upload_name)) {
					copy ($upload_name, $folder.$correct_name);
					$this->makeThumbnail($folder, basename($upload_name), 700, -1, $correct_name_700);
					$this->makeThumbnail($folder, basename($upload_name), 210, -1, $correct_name_210);
				}
				$logo = $url_path . $correct_name;
				unlink($upload_name);
			}
		
			return array($url_path,$correct_name);
		}
	
		function getImageName($folder, $filename) {
			$id = 1;
			$handler = opendir($folder);
				 		
			$path_parts = pathinfo($folder . '/' . $filename);
			 do {
			 	$filename = $path_parts['filename'] . '_' . $id . '.' . $path_parts['extension'];
			 	$id++;
			 } while(file_exists($folder . '/' . $filename)); 
			 	
			 return $filename;	 	 
		}

	function uploadSingleFile($folder, $url_path, $width) {
		$logo = '';
		if(isset($_FILES['uploadfile']) && $_FILES['uploadfile']['tmp_name'] != '' && $_FILES['uploadfile']['size'] != 0) {
			$file_upload = true;
			$real_name = basename($_FILES['uploadfile']['name']);
			$tmp_name = basename( $_FILES['uploadfile']['tmp_name']);

			$filename = $this->getCorrectFileName($real_name, $tmp_name);
			
			$upload_name = $folder . $tmp_name;
			$correct_name = $this->getImageName($folder, $filename);
			
			if(rename($_FILES['uploadfile']['tmp_name'], $upload_name)) {
				if($width != -1) {
					$this->makeThumbnail($folder, basename($upload_name), $width, -1, $correct_name);
				}				
			} 	
			$logo = $url_path . $correct_name;
			unlink($upload_name);							
		}		
		return $logo;
	}


	function uploadSingleFileNew($folder, $url_path, $width) {
		$logo = '';
		if(isset($_FILES['uploadfile']) && $_FILES['uploadfile']['tmp_name'] != '' && $_FILES['uploadfile']['size'] != 0) {
			$file_upload = true;
			$real_name = $_FILES['uploadfile']['name'];
			$tmp_name = basename( $_FILES['uploadfile']['tmp_name']);
		
			$extension_file_name = end(explode(".", $real_name));
			$filename = str_replace ('.'.$extension_file_name,'',$real_name);
			
			$filename = get_translit_singleline(iconv('utf-8','windows-1251',$filename));
			$filename .='.'.$extension_file_name;
			$upload_name = $folder . $tmp_name;
			$correct_name = $this->getImageName($folder, $filename);

			if(rename($_FILES['uploadfile']['tmp_name'], $upload_name)) {
				copy ($upload_name, $folder.'orig_'.$correct_name);
				if($width != -1) {
					$this->makeThumbnail($folder, basename($upload_name), $width, -1, $correct_name);
				}else{
	                            return $url_path.'orig_'.$correct_name;
	                        }				
			} 	
			$logo = $url_path . $correct_name;
			unlink($upload_name);							
		}	

		return $logo;
	}

	function upload_usual_file ($folder, $url_path, $name) {
		$name_of_uploaded_file = basename($_FILES[$name]['name']);
		if(isset($_FILES[$name]) && 
		   $_FILES[$name]['tmp_name'] != '' && 
		   $_FILES[$name]['size'] != 0 &&
		   $this->strpos_arr($name_of_uploaded_file, array('.zip','.jpeg','.jpg','.png','.gif','.doc','.pdf','.xls'))!==false)
		{
			$real_name = $_FILES[$name]['name'];
			$tmp_name = basename( $_FILES[$name]['tmp_name']);
			$extension_file_name = end(explode(".", $real_name));
			$filename = str_replace ('.'.$extension_file_name,'',$real_name);
			
			$filename = get_translit_singleline(iconv('utf-8','windows-1251',$filename));
			$filename .='.'.$extension_file_name;
			$upload_name = $folder . $tmp_name;
			$correct_name = $this->getImageName($folder, $filename);
			if(rename($_FILES[$name]['tmp_name'], $upload_name)) {
				copy ($upload_name, $folder.$correct_name);
			}
			$logo = $url_path . $correct_name;
			unlink($upload_name);	
			return $logo;
		}
	}

	function getCorrectFileName($real_name, $tmp_name) {
		$sanitized_name = preg_replace('/[^0-9a-z\.\_\-]/i','',$real_name);
		
		$filename = "";
		if($sanitized_name == $real_name) {
			$filename = $real_name;
		} else {
			$filename = $tmp_name;
		}
		return $filename;
	}

	function uploadFileGroup($folder, $url_path, $name)  {
		$result = array();

		if(isset($_FILES['uploadfile']) ) {		
			for($i = 0; $i < count($_FILES['uploadfile']['name']); $i++) {			
				$tmp_name =  basename($_FILES['uploadfile']['tmp_name'][$i]);			
				$size = $_FILES['uploadfile']['size'][$i];
				if($tmp_name != '' && $size != 0) {
					$real_name = $_FILES['uploadfile']['name'][$i];
					$extension_file_name = end(explode(".", $real_name));
					$filename = str_replace ('.'.$extension_file_name,'',$real_name);
					$filename .='.'.$extension_file_name;
					$upload_name = $folder . $tmp_name;
					$correct_name = $this->getImageName($folder, $filename);
					if(rename($_FILES['uploadfile']['tmp_name'][$i], $upload_name)) {
						copy ($upload_name, $folder.$correct_name);
					}
					$logo = $url_path . $correct_name;
					unlink($upload_name);	
					$result[] = $logo;
				}

			}		
		}
		return $result; 
	}

	function moveUploadedFile($real, $tmp, $folder, $path, $width) {
			
			$result = '';
			$real_name = basename($real);
			$tmp_name = basename($tmp);
				
			$filename = $this->getCorrectFileName($real_name, $tmp_name);
				
			$path_parts = pathinfo($filename);			
			$file_location =  $folder . $filename;
				
			rename($tmp, $file_location); 
					
			if($path_parts['extension'] != 'flv') {								
				$correct_name = $this->getImageName($folder, $filename);
				$file = $this->makeThumbnail($folder, basename($file_location), $width, -1, $correct_name);
				$result =  $correct_name; 
				unlink($file_location);	
			} else {
				$result =  $filename;
			}
			return $result;
		
	}

	function makeThumbnail($folder, $fileName, $dest_width, $dest_height, $thumb_name) { 

		$thumbnailPath = $folder . '/' . $thumb_name;
		$imagePath = $folder . '/' . $fileName;
		
		$imageType = array( 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP', 7 => 'TIFF', 8 => 'TIFF', 9 => 'JPC', 10 => 'JP2', 11 => 'JPX', 12 => 'JB2', 13 => 'SWC', 14 => 'IFF');
		$imgInfo = getimagesize($imagePath);

		if ( $imageType[$imgInfo[2]] == 'JPG' ) {
			$sourceImage = imagecreatefromjpeg($imagePath);
		} elseif ($imageType[$imgInfo[2]] == 'PNG'){
			$sourceImage = imagecreatefrompng($imagePath);
		} elseif ($imageType[$imgInfo[2]] == 'BMP'){
			$sourceImage = $this->imageCreateFromBMP($imagePath);
		} else {
			$sourceImage = imagecreatefromgif($imagePath);
		}
		if(!$sourceImage) {
			return false;
		}

		$size = getimagesize($imagePath);
		$src_width = $size[0];
		$src_height = $size[1];

		$x = 0; // shift top
		$y = 0; // shift left
		$calc_height = ceil($src_height * $dest_width / $src_width);

		if($dest_height == -1) {
			// just resize height proportionally
			$dest_height = $calc_height;
		} else if($calc_height > $dest_height) {
			// have to cut top and bottom
			$y = $src_height / 2 / $calc_height * ($calc_height - $dest_height);
		} else {
			// have to cut left and right
			$calc_width = ceil($src_width * $dest_height / $src_height);
			$x = $src_width / 2 / $calc_width * ($calc_width - $dest_width);
		}
		$new_im = ImageCreatetruecolor($dest_width, $dest_height);
		imagecopyresampled($new_im, $sourceImage, 0, 0, $x, $y, 
			$dest_width, $dest_height, $src_width - $x * 2, $src_height - $y * 2);
		imagejpeg($new_im,$thumbnailPath, 70);			
		return true;
	}

	function imageCreateFromBMP($filename) {

		//Ouverture du fichier en mode binaire
		if (! $f1 = fopen($filename,"rb")) return FALSE;

		//1 : Chargement des ent?tes FICHIER
		$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
		if ($FILE['file_type']	!= 19778) return FALSE;

		//2 : Chargement des ent?tes BMP
		$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
				'/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
				'/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
		$BMP['colors']	= pow(2,$BMP['bits_per_pixel']);
		if ($BMP['size_bitmap']	== 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
		$BMP['bytes_per_pixel']	= $BMP['bits_per_pixel']/8;
		$BMP['bytes_per_pixel2']= ceil($BMP['bytes_per_pixel']);
		$BMP['decal']	= ($BMP['width']*$BMP['bytes_per_pixel']/4);
		$BMP['decal']	-= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
		$BMP['decal']	= 4-(4*$BMP['decal']);
		if ($BMP['decal'] == 4) $BMP['decal']	= 0;

		//3 : Chargement des couleurs de la palette
		$PALETTE = array();
		if ($BMP['colors'] < 16777216)
		{
			$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
		}

		//4 : Cr?ation de l'image
		$IMG = fread($f1,$BMP['size_bitmap']);
		$VIDE = chr(0);

		$res = imagecreatetruecolor($BMP['width'],$BMP['height']);
		$P = 0;
		$Y = $BMP['height']-1;
		while ($Y >= 0)
		{
			$X=0;
			while ($X < $BMP['width'])
			{
				if ($BMP['bits_per_pixel']== 24)
					$COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
				elseif ($BMP['bits_per_pixel']== 16)
				{		
					$COLOR = unpack("n",substr($IMG,$P,2));
					$COLOR[1]= $PALETTE[$COLOR[1]+1];
				}
				elseif ($BMP['bits_per_pixel']== 8)
				{		
					$COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
					$COLOR[1]= $PALETTE[$COLOR[1]+1];
				}
				elseif ($BMP['bits_per_pixel']== 4)
				{
					$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
					if (($P*2)%2 == 0) 
						$COLOR[1]=($COLOR[1] >> 4); 
					else 
						$COLOR[1]=($COLOR[1] & 0x0F);
					$COLOR[1]= $PALETTE[$COLOR[1]+1];
				}
				elseif ($BMP['bits_per_pixel']	== 1)
				{
					$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
					if(($P*8)%8 == 0) $COLOR[1]= $COLOR[1]				>>7;
					elseif (($P*8)%8 == 1) $COLOR[1]=($COLOR[1]& 0x40)>>6;
					elseif (($P*8)%8 == 2) $COLOR[1]=($COLOR[1]& 0x20)>>5;
					elseif (($P*8)%8 == 3) $COLOR[1]=($COLOR[1]& 0x10)>>4;
					elseif (($P*8)%8 == 4) $COLOR[1]=($COLOR[1]& 0x8)>>3;
					elseif (($P*8)%8 == 5) $COLOR[1]=($COLOR[1]& 0x4)>>2;
					elseif (($P*8)%8 == 6) $COLOR[1]=($COLOR[1]& 0x2)>>1;
					elseif (($P*8)%8 == 7) $COLOR[1]=($COLOR[1]& 0x1);
					$COLOR[1]= $PALETTE[$COLOR[1]+1];
				}
				else
					return FALSE;
				imagesetpixel($res,$X,$Y,$COLOR[1]);
				$X++;
				$P += $BMP['bytes_per_pixel'];
			}
			$Y--;
			$P+=$BMP['decal'];
		}

		//Fermeture du fichier
		fclose($f1);

		return $res;
	}


	function strpos_arr($haystack, $needle) {
	    if(!is_array($needle)) $needle = array($needle);
	    foreach($needle as $what) {
	        if(($pos = strpos($haystack, $what))!==false) return $pos;
	    }
	    return false;
	}
}