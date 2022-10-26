<?php
class Upload {

    public $filename;
    public $newfilename;
    public $uploadfolder;
    public $allowtype = array('mp4','jpeg','jpg','gif','png');
    public $tmpfolder;
    public $randname = FALSE;
    public $error;
    public $ext;
    public $FILES;
    public $wm;
    public $watermark = '';

    public function __construct($FILES=NULL){
        if($FILES) {
            $this->FILES = $FILES;
            $extFind = explode('.',$FILES['name']);
            $this->ext = strtolower(end($extFind));
            $this->filename = str_replace(".{$this->ext}",'',$FILES['name']);
        }
    }

    function handle() {
        $this->newfilename = ($this->randname) ? rand().time().".".$this->ext : convertstring($this->filename).'.'.$this->ext;
        if($this->allowtype=='*' || in_array(strtolower($this->ext),$this->allowtype)) {
            if(!is_dir($this->tmpfolder)) mkdir($this->tmpfolder);
            if(move_uploaded_file($this->FILES['tmp_name'],$this->tmpfolder.$this->newfilename)) {
                return true;
            } else $this->error = 'Dosya taşınırken hata oluştu!';
        } else $this->error = 'Yüklemeye çalıştığınız dosya izin verilen dosyalar arasında bulunmamaktadır.!';

        return false;
    }

    public function move() {
        rename($this->tmpfolder.$this->newfilename,$this->uploadfolder.$this->newfilename);
    }

    public function resize($newFolder,$newName,$MaxWidth,$MaxHeight,$maxGenearate=false,$Quality,$watermark=FALSE) {
        list($ImageWidth,$ImageHeight,$TypeCode)=getimagesize($this->tmpfolder.$this->newfilename);
        $ImageType=($TypeCode==1?"gif":($TypeCode==2?"jpeg":($TypeCode==3?"png":FALSE)));

        $CreateFunction="imagecreatefrom".$ImageType;
        $OutputFunction="image".$ImageType;
        if ($ImageType) {
            $Ratio=($ImageHeight/$ImageWidth);
            $ImageSource=@$CreateFunction($this->tmpfolder.$this->newfilename);
            if(!$ImageSource) {
                $ImageSource = imagecreatefromstring(file_get_contents($this->tmpfolder.$this->newfilename));
                $ImageSource = imagerotate($ImageSource,90,0);
                imagejpeg($ImageSource,$this->tmpfolder.$this->newfilename,100);
                return true;
                list($ImageWidth,$ImageHeight,$TypeCode)=getimagesize($this->tmpfolder.$this->newfilename);
                $Ratio=($ImageHeight/$ImageWidth);
            }
            if ($ImageWidth > $MaxWidth || $ImageHeight > $MaxHeight) {
                if ($ImageWidth > $MaxWidth) {
                    $ResizedWidth=$MaxWidth;
                    $ResizedHeight=$ResizedWidth*$Ratio;
                }
                else {
                    $ResizedWidth=$ImageWidth;
                    $ResizedHeight=$ImageHeight;
                }
                if ($ResizedHeight > $MaxHeight) {
                    $ResizedHeight=$MaxHeight;
                    $ResizedWidth=$ResizedHeight/$Ratio;
                }

                $ResizedImage=imagecreatetruecolor($ResizedWidth,$ResizedHeight);
                if($ImageType=='png')
                {
                    imagealphablending($ResizedImage, false);
                    imagesavealpha($ResizedImage, true);
                }
                imagecopyresampled($ResizedImage,$ImageSource,0,0,0,0,$ResizedWidth,
                    $ResizedHeight,$ImageWidth,$ImageHeight);

            }
            else {
                $ResizedWidth=$ImageWidth;
                $ResizedHeight=$ImageHeight;
                $ResizedImage=$ImageSource;
            }

            if($maxGenearate) {

                $startwidth = ceil(($MaxWidth - $ResizedWidth) / 2);
                $startheight = ceil(($MaxHeight - $ResizedHeight) / 2);


                $ResizedImage1=imagecreatetruecolor($MaxWidth,$MaxHeight);
                $white = imagecolorallocate($ResizedImage1, 255, 255, 255);
                imagefill($ResizedImage1, 0, 0, $white);
                if($ImageType=='png')
                {
                    imagealphablending($ResizedImage1, false);
                    imagesavealpha($ResizedImage1, true);
                }
                imagecopyresampled($ResizedImage1,$ResizedImage, $startwidth, $startheight,0,0,$ResizedWidth,$ResizedHeight,$ResizedWidth,$ResizedHeight);

                $ResizedImage = $ResizedImage1;


            }

            $img = $ResizedImage;

            if($watermark) {
                if(file_exists('../data/uploads/'.$watermark)) {
                    $imagewidth = $ResizedWidth;
                    $imageheight = $ResizedHeight;

                    $img = imagecreatetruecolor($imagewidth, $imageheight);
                    imagecopy($img, $ResizedImage,  0, 0, 0, 0, $imagewidth, $imageheight);

                    $watermarkIMG = imagecreatefrompng('../data/uploads/'.$watermark);

                    $watermarkwidth =  imagesx($watermarkIMG);
                    $watermarkheight =  imagesy($watermarkIMG);

                    $startwidth = ($imagewidth - $watermarkwidth) / 2;
                    $startheight = ($imageheight - $watermarkheight) / 2;

                    imagecopy($img, $watermarkIMG,  $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
                }
            }

            if($ImageType=='png')
                $Quality = 9;
            $OutputFunction($img,$newFolder.$newName,$Quality);
            return true;
        }
        else
            return false;
    }
}
?>
