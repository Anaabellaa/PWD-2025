<?php
if (!empty($_POST)) {
    $file = $_POST['archivo'];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
}
?>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div id="capa_d">
        <?php if ($extension === 'pdf'): ?>
          <object data="<?= $file ?>" type="application/pdf" width="480" height="500">
            <a href="<?= $file ?>">Ver PDF</a>
          </object>
        <?php elseif ($extension === 'mp4'): ?>
          <video src="<?= $file ?>" width="680" height="500" controls></video>
        <?php elseif ($extension === 'mp3'): ?>
          <audio src="<?= $file ?>" controls></audio>
		  <?php elseif ($extension === 'opus'): ?>
          <audio src="<?= $file ?>" controls></audio>
        <?php else: ?>
          <p>Tipo de archivo no soportado.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
