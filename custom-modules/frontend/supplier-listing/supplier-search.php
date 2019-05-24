<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_GET['supplier_location']) && $_GET['supplier_location'] != ''):
    $supplierAddress = $_GET['supplier_location'];
endif;
if (isset($_GET['supplier_location_loc']) && $_GET['supplier_location_loc'] != ''):
    $supplierAddressLoc = $_GET['supplier_location_loc'];
endif;
if (isset($_GET['supplier_location_id']) && $_GET['supplier_location_id'] != ''):
    $supplierAddressID = $_GET['supplier_location_id'];
endif;
if (isset($_GET['supplier_radius']) && $_GET['supplier_radius'] != ''):
    $supplierradius = $_GET['supplier_radius'];
endif;
?>
<div class="row">
    <div class="sidebar search-sidebar">
        <div class="widget-title"><h2>Buscar Fornecedores </h2></div>
        <form name="supplierSearchFrm" id="supplierSearchFrm" action="javascript:void(0);" method="post">
            <input type="hidden" name="action" value="find_suppliers"/>
            <div class="form-group">
                <input type="text" class="form-control" id="supplier_location" name="supplier_location" value="<?php echo $supplierAddress; ?>" placeholder="Encontrar fornecedores por cidade, estado..."/>
                <input type="hidden" class="form-control" id="supplier_location_loc" name="supplier_location_loc" value="<?php echo $supplierAddressLoc; ?>"/>
                <input type="hidden" class="form-control" id="supplier_location_id" name="supplier_location_id" value="<?php echo $supplierAddressID ?>"/>
                 
            </div>
            <div class="form-group">
                <select class="form-control supplier_radius chosen" name="supplier_radius">
                     
                    <option value=" ">-Selecione a distância-</option>
                    <option value="50"><?php _e('50 Km', THEME_TEXTDOMAIN); ?></option>
                    <option value="100"><?php _e('100 Km', THEME_TEXTDOMAIN); ?></option>
                </select>
            </div>
            <div class="form-group text-center btn-sec">
                <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="supplierSearch" id="supplierSearch"><?php _e('Buscar', THEME_TEXTDOMAIN); ?></button>
            </div>
        </form>
    </div>
</div>
<br>
<?php
