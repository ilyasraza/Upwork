<?php
$mobile = 0;
$left = 44;
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
    $mobile = 1;
    $left = 27;
}
?>
<style>
    .croppie-container .cr-resizer,.croppie-container .cr-viewport{box-shadow:none !important;}
</style>
<div id="upload2" style="display:none;">
    <div id="ajaxSpinnerImage" style="display: none;position: fixed;
         opacity: 0.7;
         z-index: 9999;
         top: 212px;
         text-align: center;
         left: <?php echo $left ?>%;
         border-radius: 20px;
         padding: 10px;">
        <img style="background: center 183px no-repeat rgb(0, 0, 0);
             border-radius: 20px;
             padding: 10px;" width="160" height="160" src="cropping.gif">
    </div> 
    <div style="display:none;
         font-weight: bold;
         font-size: 28px;
         margin-top: 170px;
         color: #6FAB24;
         text-align: center;
         " id="message"> <span>Your image </span><span>is loading</span>
        <br>
        <img style="margin-top:8px;margin-bottom: 200px;" width="380" height="160" src="giphy.gif">
    </div>
    <div class="show_image_crop" id="image_container" style="display: block;border: 2px solid #AC7FAD;width: 90%;position: relative;margin: 0 auto;">
    </div>

    <div class="show_image_crop" style="display: none;position: relative;margin: 0 auto;width: 186px;margin-top: 10px;">
        <button style="margin-bottom: 10px;background: #f9b133;color: #fff7ff; border: none;cursor: pointer;padding: 3% 40%;text-align: center;font-size: 16px;" onclick="crop1()">Save</button>
    </div>
</div>
<?php
$sql = "SELECT IBS_Sizes FROM tbl_Image_Bank_Sizes WHERE IBS_ID = '" . $_REQUEST['pre_selection'] . "'";
$result = $db->get_data($sql);
$data = $result->fetch_assoc();
$pre_selection = explode('X', $data['IBS_Sizes']);
?>
<div class="main-nav-image-admin" style="width:100%">
    <div class="main-nav-image-search-admin">
        <div class="main-nav-image-search-text">
            <form onSubmit="return search_func()" enctype="multipart/form-data" id="auto_complete_form_submit">
                <div class="main-nav-image-search-image">
                    <input class="submit-autocomplete" type="submit" name="submit" value="submit">
                </div>
                <div class="main-nav-image-search-text-field">
                    <input type="text" id="autocomplete">    
                    <input type="hidden" id="keywords-searched" name="search-image" value="">    
                    <input type="hidden"  name="asset" value="<?php echo $asset ?>">    
                </div>
                <?php $multiple = array(1, 101); ?>
                <div  id="btn_upload_from_pc_library" class="main-nav-two-buttons">
                    <div id="upload_from_pc"><label style="font-family:Verdana,Arial,sans-serif;font-size:13px;" for="photo" class="daily_browse">Upload a new photo</label>
                        <input accept=".png, .jpg, .jpeg, .gif" id="photo" type="file" name="pic[]" style="display:none" <?php echo ((in_array($_REQUEST['image_id'], $multiple)) ? 'multiple' : '') ?>/>
                    </div>
                    <div id="upload_from_library"><button style="font-family:Verdana,Arial,sans-serif;font-size:13px;background: #6dac29;border: none;color: #fff7ff;" onclick="show_image()">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="main-nav-image-admin-inner">
    <ul>
        <li class="first-padding-li">
            Refine :
        </li>
        <?php if (isset($_SESSION['USER_ROLES']) && !in_array('county', $_SESSION['USER_ROLES'])) { ?>
            <li>
                <a>Website</a>
                <?php
                $sql = "SELECT R_ID, R_Name FROM tbl_Region WHERE R_Domain != '' ORDER BY R_Name ASC";
                $result = $db->get_data($sql);
                ?>     
                <ul class="region-image-bank">
                    <?php
                    $i = 0;
                    $tempReg = isset($tempregionFilter) ? explode(',', $tempregionFilter) : array();
                    while ($region = $result->fetch_assoc()) {
                        ?>
                        <div class="main-nav-image-admin-inner-sub-menu">
                            <li>
                                <div class="squaredOne">
                                    <input autocomplete="off" name="regionfilter" type="checkbox" id="<?php echo $region['R_Name'] ?>_<?php echo $region['R_ID'] ?>" value="<?php echo $region['R_ID'] ?>" <?php echo (in_array($region['R_ID'], $tempReg)) ? 'checked' : ''; ?> onchange="search_func();">
                                    <label for="<?php echo $region['R_Name'] ?>_<?php echo $region['R_ID'] ?>"></label>
                                </div>
                                <span><?php echo $region['R_Name'] ?></span>
                            </li> 
                        </div>          
                        <?php
                    }
                    ?>
                </ul>
            </li>
        <?php } ?>
        <li>
            <a>Season</a>
            <?PHP
            $sql = "SELECT * FROM tbl_Image_Bank_Season ORDER BY IBS_ID";
            $result = $db->get_data($sql);
            ?>
            <ul>
                <?php
                $i = 0;
                $tempSeason = isset($seasonFilter) ? explode(',', $seasonFilter) : array();
                while ($season = $result->fetch_assoc()) {
                    if ($i == 0) {
                        ?>
                        <div class="main-nav-image-admin-inner-sub-menu">
                            <?php
                        }
                        ?>
                        <li>
                            <div class="squaredOne">
                                <input autocomplete="off" name="seasonfilter" type="checkbox" id="<?php echo $season['IBS_Name'] ?>_<?php echo $season['IBS_ID'] ?>" value="<?php echo $season['IBS_ID'] ?>" <?php echo (in_array($season['IBS_ID'], $tempSeason)) ? 'checked' : ''; ?> onchange="search_func();">
                                <label for="<?php echo $season['IBS_Name'] ?>_<?php echo $season['IBS_ID'] ?>"></label>
                            </div>
                            <span><?php echo $season['IBS_Name'] ?></span>
                        </li>
                        <?php
                        $i++;
                        if ($i == 4) {
                            $i = 0;
                            ?> 
                        </div>          
                        <?php
                    }
                }
                ?>
            </ul>          
        </li>
        <li>
            <a>Category</a>
            <?PHP
            $sql = "SELECT C_ID, C_Name FROM tbl_Category WHERE C_Parent = 0 ORDER BY C_Order ASC";
            $result = $db->get_data($sql);
            ?>
            <ul>
                <?php
                $i = 0;
                $tempCat = isset($catFilter) ? explode(',', $catFilter) : array();
                while ($cat = $result->fetch_assoc()) {
                    if ($i == 0) {
                        ?>
                        <div class="main-nav-image-admin-inner-sub-menu">
                            <?php
                        }
                        ?>
                        <li>
                            <div class="squaredOne">
                                <input autocomplete="off" name="catfilter" type="checkbox" id="<?php echo $cat['C_Name'] ?>_<?php echo $cat['C_ID'] ?>" value="<?php echo $cat['C_ID'] ?>" <?php echo (in_array($cat['C_ID'], $tempCat)) ? 'checked' : ''; ?> onchange="search_func();">
                                <label for="<?php echo $cat['C_Name'] ?>_<?php echo $cat['C_ID'] ?>"></label>
                            </div>
                            <span><?php echo $cat['C_Name'] ?></span>
                        </li>
                        <?php
                        $i++;
                        if ($i == 4) {
                            $i = 0;
                            ?> 
                        </div>          
                        <?php
                    }
                }
                ?>
            </ul>             
        </li>
        <li>
            <a>People</a>
            <?PHP
            $sql = "SELECT * FROM tbl_Image_Bank_People ORDER BY IBP_ID";
            $result = $db->get_data($sql);
            ?>
            <ul>
                <?php
                $i = 0;
                $tempPeople = isset($peopleFilter) ? explode(',', $peopleFilter) : array();
                while ($people = $result->fetch_assoc()) {
                    if ($i == 0) {
                        ?>
                        <div class="main-nav-image-admin-inner-sub-menu">
                            <?php
                        }
                        ?>
                        <li>
                            <div class="squaredOne">
                                <input autocomplete="off" name="peoplefilter" type="checkbox" id="<?php echo $people['IBP_Name'] ?>_<?php echo $people['IBP_ID'] ?>" value="<?php echo $people['IBP_ID'] ?>" <?php echo (in_array($people['IBP_ID'], $tempPeople)) ? 'checked' : ''; ?> onchange="search_func();">
                                <label for="<?php echo $people['IBP_Name'] ?>_<?php echo $people['IBP_ID'] ?>"></label>
                            </div>
                            <span><?php echo $people['IBP_Name'] ?></span>
                        </li>
                        <?php
                        $i++;
                        if ($i == 4) {
                            $i = 0;
                            ?> 
                        </div>          
                        <?php
                    }
                }
                ?>
            </ul>
        </li>
        <?php if (isset($_SESSION['USER_ROLES']) && !in_array('county', $_SESSION['USER_ROLES'])) { ?>
            <li>
                <a>Owner</a>
                <?PHP
                $sql = "SELECT * FROM tbl_Photographer_Owner ORDER BY PO_ID";
                $result = $db->get_data($sql);
                ?>
                <ul>
                    <?php
                    $i = 0;
                    $tempOwner = isset($ownerFilter) ? explode(',', $ownerFilter) : array();
                    while ($owner = $result->fetch_assoc()) {
                        if ($i == 0) {
                            ?>
                            <div class="main-nav-image-admin-inner-sub-menu">
                                <?php
                            }
                            ?>
                            <li>
                                <div class="squaredOne">
                                    <input autocomplete="off" name="ownerfilter" type="checkbox" id="<?php echo $owner['PO_Name'] ?>_<?php echo $owner['PO_ID'] ?>" value="<?php echo $owner['PO_ID'] ?>" <?php echo (in_array($owner['PO_ID'], $tempOwner)) ? 'checked' : ''; ?> onchange="search_func();">
                                    <label for="<?php echo $owner['PO_Name'] ?>_<?php echo $owner['PO_ID'] ?>"></label>
                                </div>
                                <span><?php echo $owner['PO_Name'] ?></span>
                            </li>
                            <?php
                            $i++;
                            if ($i == 4) {
                                $i = 0;
                                ?> 
                            </div>          
                            <?php
                        }
                    }
                    ?>
                </ul>
            </li>
        <?php } ?>
        <li>
            <a>Campaign</a>
            <?PHP
            $sql = "SELECT * FROM tbl_Image_Bank_Campaign ORDER BY IBC_ID";
            $result = $db->get_data($sql);
            ?>
            <ul>
                <?php
                $i = 0;
                $tempCampaign = isset($campaignFilter) ? explode(',', $campaignFilter) : array();
                while ($campaign = $result->fetch_assoc()) {
                    if ($i == 0) {
                        ?>
                        <div class="main-nav-image-admin-inner-sub-menu">
                            <?php
                        }
                        ?>
                        <li>
                            <div class="squaredOne">
                                <input autocomplete="off" name="campaignfilter" type="checkbox" id="<?php echo $campaign['IBC_Name'] ?>_<?php echo $campaign['IBC_ID'] ?>" value="<?php echo $campaign['IBC_ID'] ?>" <?php echo (in_array($campaign['IBC_ID'], $tempCampaign)) ? 'checked' : ''; ?> onchange="search_func();">
                                <label for="<?php echo $campaign['IBC_Name'] ?>_<?php echo $campaign['IBC_ID'] ?>"></label>
                            </div>
                            <span><?php echo $campaign['IBC_Name'] ?></span>
                        </li>
                        <?php
                        $i++;
                        if ($i == 4) {
                            $i = 0;
                            ?> 
                        </div>          
                        <?php
                    }
                }
                ?>
            </ul>
        </li>
        <li class="img_order_show_hide" style="margin-bottom: 10px;margin-left: 5px;">
            <select name="img_filter" id="img_filter" style="font-size: 0.9em;width: 150px;font-family: 'news-gothic';" onchange="search_func();">
                <option value="">Select Limit </option>
                <option value="" <?php echo ($limit == '') ? 'selected' : ''; ?>>Any size </option>
                <option value="7" <?php echo ($limit == 7) ? 'selected' : ''; ?>>Below 400x300</option>
                <option value="1" <?php echo ($limit == 1) ? 'selected' : ''; ?>>B/W 400x300 & 640x480 </option>
                <option value="2" <?php echo ($limit == 2) ? 'selected' : ''; ?>>B/W 640x480 & 800x600 </option>
                <option value="3" <?php echo ($limit == 3) ? 'selected' : ''; ?>>B/W 800x600 & 1024X768 </option>
                <option value="4" <?php echo ($limit == 4) ? 'selected' : ''; ?>>B/W 1024X768 & 1600X1200 </option>
                <option value="5" <?php echo ($limit == 5) ? 'selected' : ''; ?>>B/W 1600X1200 & 2272X1704 </option>
                <option value="6" <?php echo ($limit == 6) ? 'selected' : ''; ?>>Larger than 2272X1704 </option>
            </select>
        </li>
    </ul>
</div>
</div>
<script>
    var width, height, sizes, resize = [];
    var mobile = '<?php echo $mobile ?>';
    var asset = '<?php echo $asset ?>';
    function show_image() {
        var result = image_name.split(',');
//        console.log(result);
        width = '<?php echo $pre_selection[0] ?>';
        height = '<?php echo $pre_selection[1] ?>';
        $('#message').show();
        if (asset == 1) {
            select_multiple_image(ids, <?php echo (isset($_REQUEST['image_id']) ? $_REQUEST['image_id'] : "") ?>, <?php echo $_REQUEST['iteration'] ?>, image_name, IMG_RESIZE_URL);
            jQuery('#image-library').dialog('close');
        } else {
            if (mobile == 1) {
                var width_viewport = Number(230);
                var width_boundary = Number(280);
            } else {
                var width_viewport = Number(500);
                var width_boundary = Number(600);
            }
            var v_heigth1 = Number(height) / Number(width);
            var v_heigth = Number(v_heigth1) * Number(width_viewport);
            var b_heigth1 = Number(height) / Number(width);
            var b_heigth = Number(b_heigth1) * Number(width_boundary);
            var width_v = Number(width_viewport);
            var height_v = Number(v_heigth);
            var width_b = Number(width_boundary);
            var height_b = Number(b_heigth);
            // Showing the dialog box for crop box
            var dWidth = $(window).width() * 0.6;
            var dHeight = $(window).height() * 0.9;
            jQuery('#image-library').dialog('close'); // closing the image library dialog box before showing crop box
//            console.log('2');
            $("#upload2").attr("title", "Crop & Save Image").dialog({
                width: dWidth,
                height: dHeight,
                modal: true,
                draggable: false,
                resizable: false,
                open: function () {
                    $("body").css("overflow", "hidden");
                    // It'll close the dialog box on clickicking outside the area of dialog box
                    jQuery('.ui-widget-overlay').bind('click', function () {
                        jQuery('#upload2').dialog('close');
                    });
                },
                close: function () {
                    $('body').css('overflow', 'auto');
                    $("#upload2").remove();
                }
            });
//            console.log('3');
            var images_length = result.length;
            var count_total_images = 0;
            $("#upload2").css("overflow", "hidden");
            $.each(result, function (index, value) {
                console.log(value + ' URL = ' + IMG_RESIZE_URL);
//                console.log('4');

                $('#image_container').append('<img crossorigin="anonymous" id="preview_crop' + index + '" />');
//                console.log('5');
                $('#preview_crop' + index).attr('src', IMG_RESIZE_URL + value)
                        .load(function () {
//                            console.log('6');
                            $('.show_image_crop').fadeIn(500);
                            resize[index] = $('#preview_crop' + index).croppie({
//                                enableExif: true,
                                viewport: {
                                    width: Number(width_v),
                                    height: Number(height_v),
                                    type: 'square'
                                },
                                boundary: {
                                    width: Number(width_b),
                                    height: Number(height_b)
                                },
                                enableOrientation: true
                            });

                            $('#preview_crop' + index).after('<div style="margin-top: 20px;text-align: center;" class="rot rotate' + index + '"></div>');
                            $('.rotate' + index).append('<img style="cursor:pointer" id="rotateLeft' + index + '" data-deg="+90" src="rotate.png">');
                            $("#rotateLeft" + index).click(function () {
                                resize[index].croppie('rotate', parseInt($(this).data('deg')));
                            });

                            count_total_images++;
                            if (images_length == count_total_images) {
                                $('#message').hide();
                                $("#upload2").css("overflow", "auto");
                            }
                            $(".cr-boundary").css("margin-top", "10px");

                            $(".cr-slider-wrap").css("padding-bottom", "4");
                        })
                        .error(function () {
                            alert('Error Loading Image');
                        });
            });
        }
    }
    var IMG_RESIZE_URL = '<?php echo "http://" . DOMAIN . IMG_BANK_RESIZE_REL ?>';
    var IMG_URL = '<?php echo "http://" . DOMAIN . IMG_BANK_REL ?>';
    $('#photo').change(function () {
        var numFiles = $(this).get(0).files.length;
        var FileSize = $(this).get(0).files[0].size;
        if (FileSize > 15728640) {
            swal("Image!", "File Size must be less then 15MB!", "warning");
            document.getElementById("photo").value = "";
            return false;
        }
        var numf = numFiles + Number('<?php echo $_REQUEST['limit'] ?>');
        if (numFiles > 20) {
            swal("", "You can upload only 20 photos.", "warning");
            $("#photo").val([]);
        } else if (numf > 30) {
            swal("", "You can not upload more than 30 photos in photo gallery.", "warning");
            $("#photo").val([]);
        } else {
            var form = $("#auto_complete_form_submit");
            var formData = new FormData(form[0]);
            $.ajax({
                type: 'POST',
                url: 'save_pic.php',
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (path) {
                    var idss = [];
                    var paths = [];
                    idss = path.id;
                    paths = path.path;
                    if (asset == 1) {
                        idss = idss.join();
                        select_multiple_image(idss, <?php echo (isset($_REQUEST['image_id']) ? $_REQUEST['image_id'] : "") ?>, <?php echo $_REQUEST['iteration'] ?>, paths, IMG_RESIZE_URL);
                        jQuery('#image-library').dialog('close');
                    } else {
                        ids = idss.join();
                        width = '<?php echo $pre_selection[0] ?>';
                        height = '<?php echo $pre_selection[1] ?>';
//                        $('#message').show();
                        if (mobile == 1) {
                            var width_viewport = Number(230);
                            var width_boundary = Number(280);
                        } else {
                            var width_viewport = Number(500);
                            var width_boundary = Number(600);
                        }
                        var v_heigth1 = Number(height) / Number(width);
                        var v_heigth = Number(v_heigth1) * Number(width_viewport);
                        var b_heigth1 = Number(height) / Number(width);
                        var b_heigth = Number(b_heigth1) * Number(width_boundary);
                        var width_v = Number(width_viewport);
                        var height_v = Number(v_heigth);
                        var width_b = Number(width_boundary);
                        var height_b = Number(b_heigth);
                        var dWidth = $(window).width() * 0.6;
                        var dHeight = $(window).height() * 0.9;
                        jQuery('#image-library').dialog('close');
                        $("#upload2").attr("title", "Crop & Save Image").dialog({
                            width: dWidth,
                            height: dHeight,
                            modal: true,
                            draggable: false,
                            resizable: false,
                            open: function () {
                                $("body").css("overflow", "hidden");
                                jQuery('.ui-widget-overlay').bind('click', function () {
                                    jQuery('#upload2').dialog('close');
                                });
                            },
                            close: function () {
                                $('body').css('overflow', 'auto');
                                $("#upload2").remove();
                            }
                        });
                        var images_length = paths.length;
                        var count_total_images = 0;
                        $("#upload2").css("overflow", "hidden");
                        $.each(paths, function (index, value) {
                            console.log(value + ' URL = ' + IMG_RESIZE_URL);
                            $('#image_container').append('<img crossorigin="anonymous" id="preview_crop' + index + '" />');
                            $('#preview_crop' + index).attr('src', IMG_RESIZE_URL + value)
                                    .load(function () {
                                        $('.show_image_crop').fadeIn(500);

                                        resize[index] = $('#preview_crop' + index).croppie({
                                            viewport: {
                                                width: Number(width_v),
                                                height: Number(height_v),
                                                type: 'square'
                                            },
                                            boundary: {
                                                width: Number(width_b),
                                                height: Number(height_b)
                                            },
                                            enableOrientation: true
                                        });

                                        $('#preview_crop' + index).after('<div style="margin-top: 20px;text-align: center;" class="rot rotate' + index + '"></div>');
                                        $('.rotate' + index).append('<img style="cursor:pointer" id="rotateLeft' + index + '" data-deg="+90" src="rotate.png">');
                                        $("#rotateLeft" + index).click(function () {
                                            resize[index].croppie('rotate', parseInt($(this).data('deg')));
                                        });

                                        count_total_images++;
                                        if (images_length == count_total_images) {
                                            $('#message').hide();
                                            $("#upload2").css("overflow", "auto");
                                        }
                                        $(".cr-boundary").css("margin-top", "25px");
                                        $(".cr-slider-wrap").css("padding-bottom", "4");
                                    })
                                    .error(function () {
                                        alert('Error Loading Image');
                                    });
                        });
                    }
                }
            });
        }
    });
    function crop1() {
//        console.log(1);
        $('div#ajaxSpinnerImage').show();
        var result = ids.split(',');
        var ids_length = result.length;
        var count = 0;
//        console.log(2);
        $.each(result, function (index, value) {
            resize[index].croppie('result', {
                type: 'canvas',
                size: 'original'
            }).then(function (img) {
//                console.log(3);
                $.ajax({
                    url: "crop_image.php",
                    type: "POST",
                    global: false,
                    data: {"image": img, "pic_id": value},
                    success: function (response) {
//                        console.log(4);
                        count++;
                        if (ids_length == 1) {
                            select_multiple_image(ids, <?php echo (isset($_REQUEST['image_id']) ? $_REQUEST['image_id'] : "") ?>, <?php echo $_REQUEST['iteration'] ?>, response, IMG_URL);
                            jQuery('#upload2').dialog('close');
                        } else if (count == ids_length) {
                            setTimeout('', 3000);
                            select_multiple_image(ids, <?php echo (isset($_REQUEST['image_id']) ? $_REQUEST['image_id'] : "") ?>, <?php echo $_REQUEST['iteration'] ?>);
                            jQuery('#upload2').dialog('close');
                        }
                    }
                });
            });
        });
    }
    // this will enable double click selection in other than photo gallery pages
    var focus = '<?php echo (isset($_REQUEST['image_id']) ? $_REQUEST['image_id'] : "") ?>';
    if (focus != 101 && focus != 1) {
        $('.image-hover-bank').attr('ondblclick', 'select_one()');
    }
    function select_one() {
        show_image();
    }
    function remove(id) {
        $("#" + id).remove();
        // removing the removed value from selected ids array
        var temp = 0;
        var idsArray = ids.split(",");
        if (idsArray.length == 1 && (focus == 101 || focus == 1)) {
            $('#upload_from_library').css({'display': 'none'});
        }
        for (var i = 0; i < idsArray.length; i++) {
            if (id != idsArray[i]) {
                if (temp == 0) {
                    temp = idsArray[i];
                } else {
                    temp += "," + idsArray[i];
                }
            }
        }
        // if ids is empty then put empty instead of 0
        if (temp == 0) {
            ids = '';
        } else {
            ids = temp;
        }
    }
    function remove_image(id) {
        $("#" + id).remove();
        // removing the removed value from selected ids array
        var temp = 0;
        var idsArray = image_name.split(",");
        for (var i = 0; i < idsArray.length; i++) {
            if (id != idsArray[i]) {
                if (temp == 0) {
                    temp = idsArray[i];
                } else {
                    temp += "," + idsArray[i];
                }
            }
        }
        // if ids is empty then put empty instead of 0
        if (temp == 0) {
            image_name = '';
        } else {
            image_name = temp;
        }
    }
    var ids = 0;
    var image_name;
    var dimensions;
    var limit = 30 - '<?php echo $_REQUEST['limit'] ?>';
    function select_the_image(imageName, id) {
        $('#upload_from_library').css({'display': 'block'});
        if (imageName !== '' && id !== '') {
            var check = 0;
            if (ids == 0) {
                ids = id;
                image_name = imageName;
                check = 1;
            } else if (focus == 101 || focus == 1) {
                var idsArray = ids.split(",");
                var imagesArray = image_name.split(",");
                var check_photo_gallery = 1;
                for (var i = 0; i < idsArray.length; i++) {
                    if (id == idsArray[i]) {
                        $('#bgcolor_' + id).css({'background-color': '#e8eeee'});
                        remove(id);
                        check_photo_gallery = 0;
                        break;
                    }
                }
                for (var i = 0; i < imagesArray.length; i++) {
                    if (imageName == imagesArray[i]) {
                        remove_image(imageName);
                        check_photo_gallery = 0;
                        break;
                    }
                }
                if (idsArray.length >= limit && check_photo_gallery == 1) {
                    swal("Limit Exceeded!", "You can not upload more than 30 photos in photo gallery.", "warning");
                    check_photo_gallery = 0;
                }
                if (check_photo_gallery == 1) {
                    ids += "," + id;
                    image_name += "," + imageName;
                    check = 1;
                }
            } else {
                var idsArray = ids.split(",");
                for (var i = 0; i < idsArray.length; i++) {
                    $('#bgcolor_' + idsArray[i]).css({'background-color': '#e8eeee'});
                    remove(idsArray[i]);
                }
                ids = id;
                image_name = imageName;
                check = 1;
            }
            if (check == 1) {
                $('#bgcolor_' + id).css({'background-color': '#ebdabe'});
            }
        }
        return ids;
    }
    function maintain_selection() {
        if (ids != 'undefined' && ids != '') {
            var idsArray = ids.split(",");
            for (var i = 0; i < idsArray.length; i++) {
                $('#bgcolor_' + idsArray[i]).css({'background-color': '#ebdabe'});
            }
        }
    }
    function Delete_Images(id, usage_count) {
        var msg;
        if (usage_count > 0) {
            msg = 'This image is currently active on websites. Are you sure you want to delete it?';
        } else {
            msg = 'Are you sure you want to delete this image?';
        }
        if (confirm(msg)) {
            $.post('image-bank-multiple-photo-delete.php', {
                ids: id
            }, function (done) {
                location.reload();
            });
        }
        return false;
    }
    function search_func() {
        var bl_id = '<?php echo (isset($BL_ID)) ? $BL_ID : 0; ?>';
        var image_id = '<?php echo $_REQUEST['image_id']; ?>';
        var iteration = '<?php echo $_REQUEST['iteration']; ?>';
        var asset = '<?php echo $asset; ?>';
        var keywords = $('#keywords-searched').val();
        var region = new Array();
        var season = new Array();
        var owner = new Array();
        var cat = new Array();
        var people = new Array();
        var campaign = new Array();
        var img_filter;
        $("input[name=regionfilter]:checked").each(function () {
            region.push($(this).val());
        });
        $("input[name=seasonfilter]:checked").each(function () {
            season.push($(this).val());
        });
        $("input[name=catfilter]:checked").each(function () {
            cat.push($(this).val());
        });
        $("input[name=peoplefilter]:checked").each(function () {
            people.push($(this).val());
        });
        $("input[name=ownerfilter]:checked").each(function () {
            owner.push($(this).val());
        });
        $("input[name=campaignfilter]:checked").each(function () {
            campaign.push($(this).val());
        });
        var img_filter = $('#img_filter option:selected').val();
        $.ajax({
            url: "show_image_bank_ajax.php",
            type: "GET",
            data: {
                bl_id: bl_id,
                region: region.join(','),
                season: season.join(','),
                cat: cat.join(','),
                people: people.join(','),
                owner: owner.join(','),
                campaign: campaign.join(','),
                search_image: keywords,
                image_id: image_id,
                img_filter: img_filter,
                iteration: iteration,
                asset: asset
            },
            success: function (html) {
                $(".image-bank-container").empty();
                $(".image-bank-container").html(html);
            }
        });
        return false;
    }
</script>
