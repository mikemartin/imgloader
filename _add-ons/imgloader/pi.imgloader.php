<?php
class Plugin_imgloader extends Plugin_transform {

  public function load() {
    $max_dim = 9000;
    $sizes = array(
      array('s', 600),
      array('m', 800),
      array('l', 1200),
      array('xl', 1500)
    );
    $cdn = Config::get('_cdn');
    $result = '';

    $img = $this->fetchParam('src', null, false, false, false);
    if( $this->fetchParam('inline', null, false, false, false) )
      $result = $img . '" ';

    $imgPath = Path::standardize(Path::fromAsset($img));
    if(!file_exists($imgPath)) {
      error_log($img . ' - does not exist', 0);
      $result .= ' data-error="File - '. $img .' - does not exist"';
      $result .= ' data-s="' . $img . '"';

      if( $this->fetchParam('inline', null, false, false, false) )
        $result = substr($result, 0, -1);
      return $result;
    }

    $maxH = $this->fetchParam('maxH');
    $maxW = $this->fetchParam('maxW');
    $quality = $this->fetchParam('q');
    list($width, $height) = getimagesize($imgPath);

    if(($width + $height) > $max_dim) {
      error_log($img . ' - total dimensions greater than ' . $max_dim, 0);
      $result .= ' data-error="File - '. $img .' - is too large."';
      $result .= ' data-s="' . $img . '"';

      if( $this->fetchParam('inline', null, false, false, false) )
        $result = substr($result, 0, -1);

      return $result;
    }

    foreach($sizes as $size) {
      $newImage = $img;
      $buildImage = false;
      if($maxH) {
        $maxHeight = ($size[1] < $maxH)? $size[1] : $maxH;
        if($height > $maxHeight) {
          $this->attributes['height'] = $maxHeight;
          $buildImage = true;
        }
      }
      if($maxW) {
        $maxWidth = ($size[1] < $maxW)? $size[1] : $maxW;
      } else {
        $maxWidth = $size[1];
      }

      if($width > $maxWidth) {
        $this->attributes['width'] = $maxWidth;
        $buildImage = true;
      }

      if($quality)
        $this->attributes['quality'] = $quality;

      if($buildImage) {
        $newImage = parent::index();
        $newImage = str_replace("'", "%27", $newImage);
      }

      if(!strpos($result, $newImage))
        $result .= 'data-'. $size[0] .'="'. $cdn . $newImage .'" ';


    }

    if( $this->fetchParam('inline', null, false, false, false) )
      $result = substr($result, 0, -2);

    return $result;
  }
}
?>
