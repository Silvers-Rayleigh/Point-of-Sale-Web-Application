<?php
require_once 'includes/header.php';
?>
<!-- Add jQuery library -->
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-latest.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?= base_url() ?>assets/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?= base_url() ?>assets/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?= base_url() ?>assets/js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="<?= base_url() ?>assets/js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>



<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <!-- Task Info -->
            <!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">-->
            <div class="card">
                <div class="header">
                    <h2><?php echo $lang_inventory; ?></h2>
                    <!--                    <ul class="header-dropdown m-r--5">
                    
                                            <a href="<?= base_url() ?>inventory/exportInventory" style="text-decoration: none;">
                                                <button type="button" class="btn btn-success" style="background-color: #5cb85c; border-color: #4cae4c;">
                    <?php echo $lang_export_inventory; ?>
                                                </button>
                                            </a>
                                        </ul>-->
                </div>


                <div class="row header" style="margin-top: 10px;">
                    <form action="<?= base_url() ?>inventory/searchInventory" method="get">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <div class="form-line">
                                <label class="form-label"><?php echo $lang_product_code; ?></label>
                                <input type="text" name="code" class="form-control" />

                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="form-line">
                                <label class="form-label"><?php echo $lang_product_name; ?></label>
                                <input type="text" name="name" class="form-control" />

                            </div>


                        </div>

                        <div class="col-md-3">
                            <div class="form-line">
                                <label>&nbsp;</label><br />
                                <button class="btn btn-primary" style="width: 100%;">&nbsp;&nbsp;<?php echo $lang_search; ?>&nbsp;&nbsp;</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-task-infos">
                            <thead>
                                <tr>
                                    <th width="15%"><?php echo $lang_code; ?></th>
                                    <th width="15%"><?php echo $lang_name; ?></th>
                                    <th width="15%"><?php echo $lang_total_quantity; ?></th>
                                    <!-- <th width="15%">Total Cost</th> -->
                                    <th width="10%"><?php echo $lang_action; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sort = '';

                                if (!empty($search_code)) {
                                    $sort .= " AND code LIKE '$search_code%' ";
                                }

                                if (!empty($search_name)) {
                                    $sort .= " AND name LIKE '%$search_name%' ";
                                }

                                $prodResult = $this->db->query("SELECT * FROM products WHERE created_datetime != '0000-00-00 00:00:00' $sort ");
                                $prodRows = $prodResult->num_rows();

                                $result_count = $prodRows;

                                if ($prodRows > 0) {
                                    $prodData = $prodResult->result();
                                    for ($p = 0; $p < count($prodData); ++$p) {
                                        $code = $prodData[$p]->code;
                                        $name = $prodData[$p]->name;

                                        $inv_qty = 0;

                                        $ckInvResult = $this->db->query("SELECT qty FROM inventory WHERE product_code = '$code' ");
                                        $ckInvData = $ckInvResult->result();
                                        for ($k = 0; $k < count($ckInvData); ++$k) {
                                            $ckInv_qty = $ckInvData[$k]->qty;

                                            $inv_qty += $ckInv_qty;

                                            unset($ckInv_qty);
                                        }
                                        unset($ckInvResult);
                                        unset($ckInvData);

                                        $each_cost = 0;
                                        $getCostResult = $this->db->query("SELECT purchase_price FROM products WHERE code = '$code' ");
                                        $getCostData = $getCostResult->result();

                                        $each_cost = $getCostData[0]->purchase_price;

                                        unset($getCostResult);
                                        unset($getCostData);

                                        $each_row_cost = 0;
                                        $each_row_cost = $inv_qty * $each_cost;
                                        ?>
                                        <tr>
                                            <td><?php echo $code; ?></td>
                                            <td><?php echo $name; ?></td>
                                            <td><?php echo $inv_qty; ?></td>
                                            <!-- <td><?php echo number_format($each_row_cost, 2, '.', ''); ?></td> -->
                                            <td>
                                                <a href="<?= base_url() ?>inventory/view_detail?pcode=<?php echo $code; ?>">
                                                    <i class="material-icons">remove_red_eye</i>
                                                </a>

                                               
                                            </td>
                                        </tr>
                                        <?php
                                        unset($code);
                                        unset($name);
                                    }
                                    unset($prodData);
                                } else {
                                    ?>
                                    <tr class="no-records-found">
                                        <td colspan="4"><?php echo $lang_no_match_found; ?></td>
                                    </tr>
                                    <?php
                                }
                                unset($prodResult);
                                unset($prodRows);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!--</div>-->
            <!-- #END# Task Info -->
        </div>
    </div>
</section>
<!-- Right Colmn // END -->



<?php
require_once 'includes/footer.php';
?>