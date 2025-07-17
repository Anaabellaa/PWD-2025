<?php
include("menu_bs.php");
include_once("libreria/carteles.php");

$cats = Cartel::categorias();
$categoriaDesdeMenu = isset($_GET['cat']) ? $_GET['cat'] : null;

echo '
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-4">
      <div id="capa_d">
        <h3>BIBLIOTECA T1</h3>
        <h4>Cartelera</h4>
        <ul class="nav nav-pills nav-stacked">';
        

        foreach($cats as $cat) {
          if (strtolower($cat['categoria']) !== 'ayuda') {
            echo '<li><a href="#"><span onclick="cargar(\'#capa_C\',\'mostrar_cartelera.php?b=' . $cat['categoria'] . '\')">' . $cat['categoria'] . '</span></a></li>';
          }
        }

echo '
        </ul>
      </div>
    </div>
    
    <div class="col-sm-8">
      <div id="capa_C">';

// Si se vino desde el menú con cat=Ayuda, mostrar solo los carteles de ayuda
if ($categoriaDesdeMenu && strtolower($categoriaDesdeMenu) == 'ayuda') {
    echo "<script>cargar('#capa_C','mostrar_cartelera.php?b=Ayuda');</script>";
}

echo '
      </div>
    </div>  
  </div>
</div>';
?>
