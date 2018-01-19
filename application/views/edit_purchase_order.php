<?php
require_once 'includes/header.php';
?>
<?php
$poDtaData = $this->Constant_model->getDataOneColumn('purchase_order', 'id', $id);

if (count($poDtaData) == 0) {
    redirect(base_url());
}

$po_numb = $poDtaData[0]->po_number;
$po_supplier_id = $poDtaData[0]->supplier_id;
$po_outlet_id = $poDtaData[0]->outlet_id;
$po_date = $poDtaData[0]->po_date;
$po_attachment = $poDtaData[0]->attachment_file;
$po_note = $poDtaData[0]->note;
$po_status = $poDtaData[0]->status;
?>

<script src="<?= base_url() ?>assets/js/jquery-1.11.0.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/typeahead.min.js"></script>




<!--Select Dropdown js start-->     
<link href="<?= base_url() ?>assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?= base_url() ?>assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
<!--Select Dropdown js end-->

<!-- Select2 -->
<link href="<?= base_url() ?>assets/css/select2.min.css" rel="stylesheet">

<script>
    $(document).ready(function () {
        /*
         document.getElementById("uploadBtn").onchange = function () {
         document.getElementById("uploadFile").value = this.value;
         };
         */

        $('input#typeahead').typeahead({
            name: 'typeahead',
            remote: '<?= base_url() ?>purchase_order/searchProduct?q=%QUERY',
            limit: 10
        });

        $("#addToList").click(function () {
            var row_count = document.getElementById("row_count").value;
            var pcode = document.getElementById("typeahead").value;

            if (pcode.length > 0) {

                var addNewCustomer = $.ajax({
                    url: '<?= base_url() ?>purchase_order/checkPcode?pcode=' + pcode,
                    type: 'GET',
                    cache: false,
                    data: {
                        format: 'json'
                    },
                    error: function () {
                        //alert("Sorry! we do not have stock!");
                    },
                    dataType: 'json',
                    success: function (data) {
                        var status = data.errorMsg;
                        var name = data.name;

                        if (status == "failure") {
                            alert("Invalid Product Code! Please search Product by Product Code");


                        } else {
                            var cell = $('<tr id="row_' + row_count + '"><td>' + pcode + '</td><td>' + name + '</td><td><input type="text" class="form-control" name="qty_' + row_count + '" value="1" style="width: 50%;" /></td><td><a onclick="deletediv(' + row_count + ')" style="cursor:pointer"><i class="icono-cross" style="color:#F00;"></i></a></td></tr><input type="hidden" class="form-control" name="pcode_' + row_count + '" value="' + pcode + '" />');


                            $('#addItemWrp').append(cell);


                            row_count++;

                            document.getElementById("typeahead").value = "";
                            document.getElementById("row_count").value = row_count;
                        }

                    }
                });





            } else {
                alert("Please search the product by Product Code!");
                document.getElementById("typeahead").focus();
            }

        });

    });

    function deletediv(ele) {
        $('#row_' + ele).remove();
    }

    /*
     document.addEventListener('DOMContentLoaded', function() {
     document.getElementById("addToList").addEventListener("click", handler);
     });
     
     function handler() {
     alert("A");	
     }
     */
</script>

<style type="text/css">
    .fileUpload {
        position: relative;
        overflow: hidden;
        border-radius: 0px;
        margin-left: -4px;
        margin-top: -2px;
    }
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .typeahead, .tt-query, .tt-hint {
        border: 1px solid #CCCCCC;
        border-radius: 4px;
        font-size: 14px;
        height: 40px;
        line-height: 30px;
        outline: medium none;
        padding: 8px 12px;
        width: 312px;
    }
    .typeahead {
        background-color: #FFFFFF;
    }
    .typeahead:focus {
        border: 2px solid #0097CF;
    }
    .tt-query {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    }
    .tt-hint {
        color: #999999;
    }
    .tt-dropdown-menu {
        background-color: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        margin-top: 0px;
        padding: 8px 0;
        width: 312px;
    }
    .tt-suggestion {
        font-size: 14px;
        line-height: 24px;
        padding: 3px 20px;
    }
    .tt-suggestion.tt-is-under-cursor {
        background-color: #0097CF;
        color: #FFFFFF;
    }
    .tt-suggestion p {
        margin: 0;
    }
</style>



<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <ol class="breadcrumb breadcrumb-bg-cyan">
                    <li><a href="<?php echo base_url() ?>"><i class="material-icons">home</i> Home</a></li>
                    <li><a href="<?php echo base_url() ?>purchase_order/po_view"><i class="material-icons">shopping_basket</i> Purchase Order</a></li>
                    
                       <li><i class="material-icons">edit_mode</i> <?php if ($po_status == '1'){ echo $lang_edit_po_before_sent; } else { echo  $lang_sent_to_supplier ; }?></li>
                            
                </ol>

                <form action="<?= base_url() ?>purchase_order/updatePO" method="post" enctype="multipart/form-data">

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php
                                    if (!empty($alert_msg)) {
                                        $flash_status = $alert_msg[0];
                                        $flash_header = $alert_msg[1];
                                        $flash_desc = $alert_msg[2];
                                        ?>
                                        <?php if ($flash_status == 'failure') { ?>
                                            <div class="alert alert-info" id="notificationWrp">
                                                <strong>Heads up!</strong> <?php echo $flash_desc; ?>
                                            </div>
                                        <?php } ?>


                                        <?php if ($flash_status == 'success') { ?>
                                            <div class="alert alert-success" id="notificationWrp">
                                                <strong>Well done!</strong> <?php echo $flash_desc; ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>



                                    <div class="header">
                                        <?php
                                        if ($po_status == '1') {
                                            ?>
                                            <h3 class="card-inside-title"><?php echo $lang_edit_po_before_sent; ?></h3>

                                            <?php
                                        } else {
                                            ?>
                                            <h3 class="card-inside-title"><?php echo $lang_sent_to_supplier; ?></h3>

                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($po_status != '1') {
                                            ?>
                                            <ul class="header-dropdown m-r--5">

                                                <a href="<?= base_url() ?>purchase_order/exportPurchaseOrder?id=<?php echo $id; ?>" style="text-decoration: none;" target="_blank">
                                                    <button type="button" info="" class="btn btn-success" style="background-color: #5cb85c; border-color: #4cae4c;">
                                                        <?php echo $lang_print_purchase_order; ?>
                                                    </button>
                                                </a>
                                            </ul>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <label class="form-label"><?php echo $lang_purchase_order_number; ?></label>
                                                <input type="text" name="po_number" class="form-control" maxlength="250" autofocus required autocomplete="off"  value="<?php echo $po_numb; ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <label class="form-label"><?php echo $lang_outlets; ?> <span style="color: #F00">*</span></label>
                                                <select name="outlet" class="form-control show-tick" data-live-search="true" required>
                                                    <option value=""><?php echo $lang_choose_outlet; ?></option>
                                                    <?php
                                                    if ($user_role == 1) {
                                                        $outletData = $this->Constant_model->getDataOneColumnSortColumn('outlets', 'status', '1', 'name', 'ASC');
                                                    } else {
                                                        $outletData = $this->Constant_model->getDataOneColumn('outlets', 'id', "$user_outlet");
                                                    }
                                                    for ($u = 0; $u < count($outletData); ++$u) {
                                                        $outlet_id = $outletData[$u]->id;
                                                        $outlet_name = $outletData[$u]->name;
                                                        ?>
                                                        <option value="<?php echo $outlet_id; ?>" <?php
                                                        if ($po_outlet_id == $outlet_id) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>
                                                                    <?php echo $outlet_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-line">

                                                <label class="form-label"><?php echo $lang_suppliers; ?> <span style="color: #F00">*</span></label>
                                                <select name="supplier" class="form-control show-tick" data-live-search="true" required>
                                                    <option value=""><?php echo $lang_choose_supplier; ?></option>
                                                    <?php
                                                    $supplierData = $this->Constant_model->getDataOneColumnSortColumn('suppliers', 'status', '1', 'name', 'ASC');
                                                    for ($s = 0; $s < count($supplierData); ++$s) {
                                                        $supplier_id = $supplierData[$s]->id;
                                                        $supplier_name = $supplierData[$s]->name;
                                                        ?>
                                                        <option value="<?php echo $supplier_id; ?>" <?php
                                                        if ($po_supplier_id == $supplier_id) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>
                                                                    <?php echo $supplier_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>







                                    </div>


                                    <div class="row clearfix">
                                        <div class="col-md-7">
                                            <div class="form-group form-float" style="padding-top: 10px;" >
                                                <div class="form-line">
                                                    <textarea class="form-control" name="note"  rows="5" required><?php echo $po_note; ?></textarea>
                                                    <label class="form-label"><?php echo $lang_note; ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group  " style="padding-top: 30px;">
                                                <div class="form-line"  >
                                                    <input type="text" name="po_date" value="<?php echo date($dateformat, time()); ?>" readonly class="form-control" />
                                                    <label class="form-label"><?php echo $lang_created_date; ?> </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="col-sm-3" style="padding-top: 30px;">
                                            <div class="form-line">

                                                <label class="form-label"><?php echo $lang_purchase_order_status; ?> <span style="color: #F00">*</span></label>
                                                <select name="po_status" class="form-control show-tick" data-live-search="true" required>
                                                    <?php
                                                    $poStatusData = $this->Constant_model->getDataAll('purchase_order_status', 'id', 'ASC');
                                                    for ($ps = 0; $ps < count($poStatusData); ++$ps) {
                                                        $poStatus_id = $poStatusData[$ps]->id;
                                                        $poStatus_name = $poStatusData[$ps]->name;

                                                        if ($poStatus_id == '3') {
                                                            continue;
                                                        }

                                                        if ($po_status == '1') {
                                                            if ($poStatus_id == '2') {
                                                                $poStatus_name = $lang_sent_to_supplier;
                                                            }
                                                            if ($poStatus_id == '1') {
                                                                $poStatus_name = $lang_created;
                                                            }
                                                        }
                                                        ?>
                                                        <option value="<?php echo $poStatus_id; ?>" <?php
                                                        if ($poStatus_id == $po_status) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>
                                                                    <?php echo $poStatus_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>


                                    <!--                                             <div class="form-line">
                                                                                     
                                                                                 </div>-->

                                    <div class="row clearfix" style=" padding-right: 10px;padding-left: 10px;">
                                        <div class="col-md-12" style="border-top: 1px solid #ccc; padding-bottom: 20px;" ></div>
                                    </div>
                                    <?php
                                    if ($po_status == '1') {
                                        ?>
                                        <div class="row clearfix">
                                            <div class="col-sm-3">
                                                <div class="form-group form-float">


                                                    <label class="form-label"><?php echo $lang_search_product; ?> <span style="color: #F00">*</span></label>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-line">

                                                    <select id="typeahead"  class="add_product_po form-control"  required>
                                                        <option value=""><?php echo $lang_search_product_by_namecode; ?></option>
                                                        <?php
                                                        $prodData = $this->Constant_model->getDataAll('products', 'id', 'DESC');
                                                        for ($p = 0; $p < count($prodData); ++$p) {
                                                            $prod_code = $prodData[$p]->code;
                                                            $prod_name = $prodData[$p]->name;
                                                            ?>
                                                            <option value="<?php echo $prod_code; ?>">
                                                                <?php echo $prod_name . ' [' . $prod_code . ']'; ?>
                                                            </option>
                                                            <?php
                                                            unset($prod_code);
                                                            unset($prod_name);
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-3" >
                                                <div class="form-line">
                                                    <div class="header">
                                                        <ul class="header-dropdown m-r--5">

                                                            <button class="btn btn-primary"  id="addToList" ><?php echo $lang_add_to_list; ?></button>

                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-hover dashboard-task-infos">
                                                <thead>
                                                    <tr>
                                                        <th width="30%" style="background-color: #686868; color: #FFF;"><?php echo $lang_product_name; ?></th>
                                                        <th width="30%" style="background-color: #686868; color: #FFF;"><?php echo $lang_product_code; ?></th>
                                                        <th width="30%" style="background-color: #686868; color: #FFF;"><?php echo $lang_order_qty; ?></th>
                                                        <th width="10%" style="background-color: #686868; color: #FFF;"><?php echo $lang_action; ?></th>
                                                    </tr>
                                                </thead>

                                                <?php
                                                $poItemData = $this->Constant_model->getDataOneColumnSortColumn('purchase_order_items', 'po_id', $id, 'id', 'ASC');
                                                for ($pi = 0; $pi < count($poItemData); ++$pi) {
                                                    $po_item_id = $poItemData[$pi]->id;
                                                    $po_item_pcode = $poItemData[$pi]->product_code;
                                                    $po_item_qty = $poItemData[$pi]->ordered_qty;

                                                    $poNameResult = $this->db->query("SELECT * FROM products WHERE code = '$po_item_pcode' ");
                                                    $poNameData = $poNameResult->result();

                                                    $po_name = $poNameData[0]->name;
                                                    ?><tr>
                                                        <td><?php echo $po_item_pcode; ?></td>
                                                        <td><?php echo $po_name; ?></td>
                                                        <td>
                                                            <input type="text" name="existQty_<?php echo $po_item_id; ?>" class="form-control" value="<?php echo $po_item_qty; ?>" style="width: 50%;" <?php
                                                            if ($po_status != '1') {
                                                                echo 'readonly';
                                                            }
                                                            ?> />
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($po_status == '1') {
                                                                ?>
                                                                <a href="<?= base_url() ?>purchase_order/deletePOItem?po_item_id=<?php echo $po_item_id; ?>&po_id=<?php echo $id; ?>" onclick="return confirm('Are you confirm to delete Purchase Order Item?')">
                                                                    <i class="icono-cross" style="color:#F00;"></i>
                                                                </a>
                                                                <?php
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tbody id="addItemWrp">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12" style="padding-top: 30px;">
                                            <div class="form-line">
                                                <div class="header">
                                                    <input type="hidden" id="row_count" name="row_count" value="1" />
                                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                                    <?php
                                                    if ($po_status == '1') {
                                                        ?>
                                                        <button class="btn btn-primary" ><?php echo $lang_update_purchase_order; ?></button>
                                                        <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div><!-- Panel body // END -->

                            </div><!-- Panel default // END -->




                        </div><!-- Col Md 12 -->
                    </div><!-- row end -->
                </form>
            </div><!-- col-xs-12  // END -->
        </div><!-- Row // END -->
    </div><!-- container-fluid end -->

</section><!-- Right Colmn // END -->


<?php
require_once 'includes/footer.php';
?>

<script src="<?= base_url() ?>assets/js/select2.full.min.js"></script>
<!-- Select2 -->
<script>
                                                            $(document).ready(function () {
                                                                $(".add_product_po").select2({
                                                                    placeholder: "<?php echo $lang_search_product_by_namecode; ?>",
                                                                    allowClear: true
                                                                });
                                                            });
</script>

