<?php
session_cache_limiter('nocache');
session_start();

if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {

        // connesione al file Mage.php dell'istallazione di Magento
        include("percorsoMage.php");
        require_once "../".$MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        require('sorter.php');

        // recupero il valore della select, il mode che rappresenta il livello di sottocategoria e il parent (se esiste) della sottocategoria
        $valore=$_REQUEST['valore'];
        $mode=$_REQUEST['mode'];
        $parent=$_REQUEST['parent'];

        // se il livello della sottocategoria è > 1, al parent aggiungo il valore della select corrente e uno slash. Questo serve per tenere traccia delle sottocategorie parent e per mantenere selezionato il valore delle rispettive sottocategorie parent
        if ($mode>1){
            $parent.=$valore."/";
        }

        // recupero tutte le sottocategorie relative alla sottocategoria con id '$valore'
        $i=0;
        $cat = Mage::getModel('catalog/category')->load($valore);
        $subcats = $cat->getChildren();
        foreach(explode(',',$subcats) as $subCatid)
        {
            // controllo se esistono sottocategorie
            if ($subCatid!=""){
                $parentCategory = Mage::getModel('catalog/category')->load($subCatid);
                $categoria[$i][0]=$subCatid;
                $categoria[$i][1]=$parentCategory->getName();
                $categoria[$i][2]=$mode;
                $categoria[$i][3]=$parent;

                $nameParent=Mage::getModel('catalog/category')->load($cat->getParentId())->getName();
    

                if ($cat->getName()=="Borse donna" || $nameParent=="Borse donna"){
                    $categoria[$i][4]="Borse donna";
                }
                else if ($cat->getName()=="Borse uomo" || $nameParent=="Borse uomo"){
                    $categoria[$i][4]="Borse uomo";
                }
                else if ($cat->getName()=="Accessori donna" || $nameParent=="Accessori donna"){
                    $categoria[$i][4]="Accessori donna";
                }
                else if ($cat->getName()=="Accessori uomo" || $nameParent=="Accessori uomo"){
                    $categoria[$i][4]="Accessori uomo";
                }
                else if ($cat->getName()=="Calzature donna" || $nameParent=="Calzature donna"){
                    $categoria[$i][4]="Calzature donna";
                }
                else if ($cat->getName()=="Calzature uomo" || $nameParent=="Calzature uomo"){
                    $categoria[$i][4]="Calzature uomo";
                }
                else if ($cat->getName()=="Abiti"){
                    $categoria[$i][4]="Abiti";
                }
                else if ($cat->getName()=="Topwear" || $nameParent=="Topwear"){
                    $categoria[$i][4]="Topwear";
                }
                else if ($cat->getName()=="Pantaloni" || $nameParent=="Pantaloni"){
                    $categoria[$i][4]="Pantaloni";
                }
                else if ($cat->getName()=="Jeans" || $nameParent=="Jeans"){
                    $categoria[$i][4]="Jeans";
                }
                else if ($cat->getName()=="Gonne"){
                    $categoria[$i][4]="Gonne";
                }
                else if (($cat->getName()=="Camicie" && $cat->getId()==9)||($nameParent=="Camicie" && $cat->getParentId()==9)){
                    $categoria[$i][4]="Camicie donna";
                }
                else if ($cat->getName()=="Camicie" && $cat->getId()==22){
                    $categoria[$i][4]="Camicie uomo";
                }
                else if ($cat->getName()=="Capispalla" || $nameParent=="Capispalla"){
                    $categoria[$i][4]="Capispalla";
                }
                else if ($cat->getName()=="Giacche/blazer" || $cat->getName()=="Blazer"){
                    $categoria[$i][4]="Giacche";
                }
                else if ($cat->getName()=="Cinture"){
                    $categoria[$i][4]="Cinture";
                }
                else {
                    $categoria[$i][4]="";
                }
                $i=$i+1;
            }
            else {
                $categoria[0][0]="";
                $categoria[0][1]="...";
                $categoria[0][2]=$mode;
                $categoria[0][3]=$parent;

                $nameParent=Mage::getModel('catalog/category')->load($cat->getParentId())->getName();
                if ($cat->getName()=="Borse donna" || $nameParent=="Borse donna"){
                    $categoria[0][4]="Borse donna";
                }
                else if ($cat->getName()=="Borse uomo" || $nameParent=="Borse uomo"){
                    $categoria[0][4]="Borse uomo";
                }
                else if ($cat->getName()=="Accessori donna" || $nameParent=="Accessori donna"){
                    $categoria[0][4]="Accessori donna";
                }
                else if ($cat->getName()=="Accessori uomo" || $nameParent=="Accessori uomo"){
                    $categoria[0][4]="Accessori uomo";
                }
                else if ($cat->getName()=="Calzature donna" || $nameParent=="Calzature donna"){
                    $categoria[0][4]="Calzature donna";
                }
                else if ($cat->getName()=="Calzature uomo" || $nameParent=="Calzature uomo"){
                    $categoria[0][4]="Calzature uomo";
                }
                else if ($cat->getName()=="Abiti"){
                    $categoria[0][4]="Abiti";
                }
                else if ($cat->getName()=="Topwear" || $nameParent=="Topwear"){
                    $categoria[0][4]="Topwear";
                }
                else if ($cat->getName()=="Pantaloni" || $nameParent=="Pantaloni"){
                    $categoria[0][4]="Pantaloni";
                }
                else if ($cat->getName()=="Jeans" || $nameParent=="Jeans"){
                    $categoria[0][4]="Jeans";
                }
                else if ($cat->getName()=="Gonne"){
                    $categoria[0][4]="Gonne";
                }
                else if (($cat->getName()=="Camicie" && $cat->getId()==9)||($nameParent=="Camicie" && $cat->getParentId()==9)){
                    $categoria[0][4]="Camicie donna";
                }
                else if ($cat->getName()=="Camicie" && $cat->getId()==22){
                    $categoria[0][4]="Camicie uomo";
                }
                else if ($cat->getName()=="Capispalla" || $nameParent=="Capispalla"){
                    $categoria[0][4]="Capispalla";
                }
                else if ($cat->getName()=="Giacche/blazer" || $cat->getName()=="Blazer"){
                    $categoria[0][4]="Giacche";
                }
                else if ($cat->getName()=="Cinture"){
                    $categoria[0][4]="Cinture";
                }
                else {
                    $categoria[0][4]="";
                }
                break;
            }
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($categoria);
        $categoria=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);

        echo json_encode($categoria);
    }
    else {
        echo "<script>alert('Non è possibile visualizzare questa pagina!');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}
?>