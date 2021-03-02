<?php

require_once '../../../include/config.inc.php';
require_once '../../../include/login.inc.php';
require('tcpdf_include.php');
$limits = explode(',', $_SESSION['USER_LIMIT']);
if (!in_array('regions', $_SESSION['USER_PERMISSIONS']) && (!in_array('manage-county-region', $_SESSION['USER_PERMISSIONS']) || !in_array($_REQUEST['rid'], $limits))) {
    header("Location: /admin/");
    exit();
}

$regionID = ($_REQUEST['rid'] > 0) ? $_REQUEST['rid'] : 0;

function clean($string) {
    $string = strtolower(str_replace(' ', '-', $string)); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

if ($regionID > 0) {
    $sql = "SELECT * FROM tbl_Region WHERE R_ID = " . $regionID;
    $result = $db->get_data($sql);
    $activeRegion = $result->fetch_assoc();

    $regionList = '';
    if ($activeRegion['R_Parent'] == 0 && $activeRegion['R_Type'] != 4) {
        $sql = "SELECT RM_Child FROM tbl_Region_Multiple LEFT JOIN tbl_Region ON RM_Child = R_ID WHERE RM_Parent = '" . $db->encode_strings($activeRegion['R_ID']) . "'";
        $result = $db->get_data($sql);
        $first = true;
        $regionList .= "(";
        while ($row = $result->fetch_assoc()) {
            if ($first) {
                $first = false;
            } else {
                $regionList .= ",";
            }
            if ($event_counter == 0) {
                $operator = " AND (";
            } else {
                $operator = " OR";
            }
            $regionList .= $row['RM_Child'];
        }
        $regionList .= ")";
    }

    ///// Include Free listings
    if ($activeRegion['R_Include_Free_Listings'] == 1) {
        $include_free_listings = '';
    } else {
        $include_free_listings = ' AND BL_Listing_Type > 1';
    }
    if ($REGION['R_Order_Listings_Manually'] == 1) {
        $order_listings = 'ORDER BY BLO_Order ASC, LT_Order DESC, BL_Listing_Title';
    } else {
        $order_listings = 'ORDER BY BL_Points DESC, LT_Order DESC, BL_Listing_Title';
    }
} else {
    header("Location: /admin/regions.php");
    exit();
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tourist Town');
$pdf->SetTitle('Sitemap');
$pdf->SetSubject('Sitemap');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('dejavusans', '', 14);

// add a page
$pdf->AddPage();

$text = '<ul>
            <li><a href="https://' . $activeRegion['R_Domain'] . '/" target="_blank">Home</a></li>';
//Main Categories
$sql = "SELECT C_ID, RC_C_ID, RC_Link, C_Name_SEO, C_Name, MN_ID, RC_Name, RC_Status, MN_Order,C_Is_Blog FROM tbl_Main_Navigation
        LEFT JOIN tbl_Region_Category ON RC_C_ID = MN_C_ID AND RC_R_ID = MN_R_ID
        LEFT JOIN tbl_Category ON C_ID = MN_C_ID AND C_Parent = 0
        WHERE MN_R_ID = '" . $activeRegion['R_ID'] . "' AND C_ID != 8 AND MN_Static_Links = 0 AND C_Is_Blog = 0 AND RC_Status = 0 GROUP BY MN_ID ORDER BY MN_Order ASC";
$result = $db->get_data($sql);
while ($row = $result->fetch_assoc()) {
    $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/' . $row['C_Name_SEO'] . '/" target="_blank">' . (($row['RC_Name'] != "") ? $row['RC_Name'] : $row['C_Name']) . '</a>';
    $sql1 = "SELECT RC_Name, C_ID, C_Parent, C_Order, C_Name_SEO, C_Name, RC_Link FROM tbl_Category 
            LEFT JOIN tbl_Region_Category ON RC_C_ID = C_ID AND RC_R_ID = " . $db->encode_strings($activeRegion['R_ID']) . "
            INNER JOIN tbl_Business_Listing_Category ON BLC_C_ID = C_ID   
            INNER JOIN tbl_Business_Listing_Category_Region ON BLC_BL_ID = BLCR_BL_ID AND BLCR_BLC_R_ID " . $db->encode_strings((($activeRegion['R_Parent'] == 0 && $activeRegion['R_Type'] != 4 ) ? "IN " . $regionList : "= " . $activeRegion['R_ID'])) . "
            INNER JOIN tbl_Business_Listing ON BL_ID = BLC_BL_ID
            WHERE C_Parent = '" . $db->encode_strings($row['C_ID']) . "' and RC_Status = 0 AND RC_R_ID > 0 AND hide_show_listing = '1' $include_free_listings
            GROUP BY C_ID ORDER BY RC_Order ASC";
    $result1 = $db->get_data($sql1);
    $count = $result1->num_rows;
    if ($count > 0) {
        $text .= '<ul>';
        while ($row1 = $result1->fetch_assoc()) {
            $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/' . $row['C_Name_SEO'] . '/' . $row1['C_Name_SEO'] . '/" target="_blank"> ' . (($row1['RC_Name'] != "") ? $row1['RC_Name'] : $row1['C_Name']) . '</a>';
            if ($row1['C_ID'] == 75) {
                $sql2 = "SELECT DISTINCTROW BL_ID, BL_Listing_Title, BL_Name_SEO, BL_Photo, BL_Photo_Alt, hide_show_listing, BL_Free_Listing_Status, BL_Free_Listing_Status_Date
                        FROM tbl_Business_Listing 
                        INNER JOIN tbl_Business_Listing_Category ON BLC_BL_ID = BL_ID 
                        INNER JOIN tbl_Business_Listing_Category_Region ON  BLCR_BL_ID = BL_ID
                        INNER JOIN tbl_Business_Listing_Ammenity ON BLA_BL_ID = BL_ID
                        LEFT JOIN tbl_Listing_Type ON LT_ID = BL_Listing_Type
                        WHERE BLCR_BLC_R_ID " . $db->encode_strings((($activeRegion['R_Parent'] == 0 && $activeRegion['R_Type'] != 4 ) ? "IN " . $regionList : "= " . $activeRegion['R_ID'])) . "
                        AND BLA_BA_ID = 45 AND hide_show_listing = '1' $include_free_listings 
                        GROUP BY BL_ID ORDER BY BL_Points DESC, BL_Listing_Title";
            } else {
                $sql2 = "SELECT DISTINCTROW BL_ID, BL_Listing_Title, BL_Name_SEO, BL_Photo, BL_Photo_Alt, hide_show_listing, BL_Free_Listing_Status, BL_Free_Listing_Status_Date
                        FROM tbl_Business_Listing 
                        INNER JOIN tbl_Business_Listing_Category ON BLC_BL_ID = BL_ID AND BLC_C_ID = '" . $db->encode_strings($row1['C_ID']) . "'
                        INNER JOIN tbl_Business_Listing_Category_Region ON  BLCR_BL_ID = BL_ID 
                        LEFT JOIN tbl_Listing_Type ON LT_ID = BL_Listing_Type
                        LEFT JOIN tbl_Business_Listing_Order ON BL_ID = BLO_BL_ID AND BLO_R_ID = '" . $db->encode_strings($activeRegion['R_ID']) . "' 
                        AND BLO_S_C_ID = BLC_C_ID
                        WHERE BLCR_BLC_R_ID " . $db->encode_strings((($activeRegion['R_Parent'] == 0 && $activeRegion['R_Type'] != 4) ? "IN " . $regionList : "= " . $activeRegion['R_ID'])) . " AND hide_show_listing='1' 
                        $include_free_listings $order_listings";
            }
            $result2 = $db->get_data($sql2);
            $count2 = $result2->num_rows;
            if ($count2 > 0) {
                $text .= '<ul>';
                while ($row2 = $result2->fetch_assoc()) {
                    $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/profile/' . $row2['BL_Name_SEO'] . '/' . $row2['BL_ID'] . '/" target="_blank">' . $row2['BL_Listing_Title'] . '</a></li>';
                }
                $text .= '</ul>';
            }
            $text .= '</li>';
        }
        $sql_day_trip = "SELECT DISTINCTROW BL_ID, BL_Listing_Title, BL_Name_SEO, BL_Photo, BL_Photo_Alt, hide_show_listing, BL_Free_Listing_Status
                         FROM tbl_Business_Listing LEFT JOIN tbl_Business_Listing_Daytrip ON BLD_BL_ID = BL_ID  
                         WHERE BLD_R_ID = '" . $activeRegion['R_ID'] . "' $include_free_listings ORDER BY BL_Listing_Title";
        $result_day_trip = $db->get_data($sql_day_trip);
        $rowcount = $result_day_trip->num_rows;
        if ($activeRegion['R_Day_Trip'] == 1 && $rowcount != 0 && ($row['C_ID'] == 5 || $row['RC_C_ID'] == 5)) {
            $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/' . $row['C_Name_SEO'] . '/day-trip" target="_blank">Day Trips</a></li>';
            $text .= '<ul>';
            while ($row_day_trip = $result_day_trip->fetch_assoc()) {
                $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/profile/' . $row_day_trip['BL_Name_SEO'] . '/' . $row_day_trip['BL_ID'] . '/" target="_blank">' . $row_day_trip['BL_Listing_Title'] . '</a></li>';
            }
            $text .= '</ul>';
        }
        $text .= '</ul>';
    }
    $text .= '</li>';
}
//Events
if ($activeRegion['R_Show_Hide_Event'] == 1) {
    $sql_event = "SELECT C_ID, RC_C_ID, RC_Link, C_Name_SEO, C_Name, MN_ID, RC_Name, MN_Static_Links, RC_Status, MN_Order,C_Is_Blog FROM tbl_Main_Navigation 
LEFT JOIN tbl_Region_Category ON RC_C_ID = MN_C_ID AND RC_R_ID = MN_R_ID
LEFT JOIN tbl_Category ON C_ID = MN_C_ID AND C_Parent = 0     
WHERE MN_R_ID = '" . $activeRegion['R_ID'] . "' AND C_ID = 8 AND C_Is_Blog = 0 AND RC_Status = 0 GROUP BY MN_ID ORDER BY MN_Order ASC";
    $result_event = $db->get_data($sql_event);
    while ($row_event = $result_event->fetch_assoc()) {
        $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/' . $row_event['C_Name_SEO'] . '/" target="_blank">' . (($row_event['RC_Name'] != "") ? $row_event['RC_Name'] : $row_event['C_Name']) . '</a>';
        //Getting Local Events from DB
        $sql_event1 = "SELECT EventDateStart, EventID, Title, E_Name_SEO, Content, EventDateEnd, Pending, E_Town FROM Events_master  WHERE Pending = 0";
        $E_Region_ID = "";
        if ($activeRegion['R_Parent'] == 0 && $activeRegion['R_Type'] != 4) {
            $EP = "SELECT RM_Child FROM tbl_Region_Multiple LEFT JOIN tbl_Region ON R_ID = RM_Parent WHERE R_ID = '" . $db->encode_strings($activeRegion['R_ID']) . "'";
            $resultEP = $db->get_data($EP);
            $event_counter = 0;
            while ($rowEP = $resultEP->fetch_assoc()) {
                if ($event_counter == 0) {
                    $operator = " AND (";
                } else {
                    $operator = " OR";
                }
                $E_Region_ID .= " $operator FIND_IN_SET (" . $rowEP['RM_Child'] . ", E_Region_ID)";
                $event_counter++;
            }
            $E_Region_ID .= " $operator FIND_IN_SET (" . $activeRegion['R_ID'] . ", E_Region_ID) )";
        } else {
            $E_Region_ID = " AND FIND_IN_SET (" . $activeRegion['R_ID'] . ", E_Region_ID)";
        }
        $sql_event1 .= " AND (EventDateStart >= CURDATE() OR ( EventDateEnd >= CURDATE( ) AND EventDateStart <= CURDATE( ))) $E_Region_ID ORDER BY EventDateStart";
        // obtain the count of the number of rows in the current query
        // instantiate the Paginate object with said number of rows
        $result_event1 = $db->get_data($sql_event1);
        $totalEvents = $result_event1->num_rows;
        if ($totalEvents > 0) {
            $text .= '<ul>';
            while ($row_event1 = $result_event1->fetch_assoc()) {
                $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/events/' . $row_event1['E_Name_SEO'] . '/' . $row_event1['EventID'] . '" target="_blank">' . $row_event1['Title'] . '</a></li>';
            }
            $text .= '</ul>';
        }
        $text .= '</li>';
    }
}
//Blogs
if (isset($activeRegion['R_Stories']) && $activeRegion['R_Stories'] == 1) {
    $sql_story = "SELECT C_ID, RC_C_ID, RC_Link, C_Name_SEO, C_Name, MN_ID, RC_Name, MN_Static_Links, RC_Status, MN_Order,C_Is_Blog FROM tbl_Main_Navigation 
LEFT JOIN tbl_Region_Category ON RC_C_ID = MN_C_ID AND RC_R_ID = MN_R_ID
LEFT JOIN tbl_Category ON C_ID = MN_C_ID AND C_Parent = 0     
WHERE MN_R_ID = '" . $activeRegion['R_ID'] . "' AND C_Is_Blog = 1 AND RC_Status = 0 GROUP BY MN_ID ORDER BY MN_Order ASC";
    $result_story = $db->get_data($sql_story);
    while ($row_story = $result_story->fetch_assoc()) {
        $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/' . $row_story['C_Name_SEO'] . '/" target="_blank">' . (($row_story['RC_Name'] != "") ? $row_story['RC_Name'] : $row_story['C_Name']) . '</a>';
        $sql_story1 = "SELECT C_ID, C_Name, RC_Name FROM tbl_Region_Category LEFT JOIN tbl_Category ON RC_C_ID = C_ID 
    WHERE C_Parent = '" . $db->encode_strings($row_story['C_ID']) . "' AND RC_R_ID = " . $activeRegion['R_ID'] . " AND RC_Status = 0 
    GROUP BY C_ID ORDER BY RC_Order ASC";
        $result_story1 = $db->get_data($sql_story1);
        while ($row_story1 = $result_story1->fetch_array()) {
            $contid[] = $row_story1['C_ID'];
        }
        $child = implode(',', $contid);
        if ($child != '') {
            $sql_story2 = "SELECT S_ID, SC_Category, S_Active, S_Title, S_Thumbnail, C_Name, C_Parent, RC_Name FROM tbl_Story 
    INNER JOIN tbl_Story_Region ON S_ID = SR_S_ID
    LEFT JOIN tbl_Story_Category ON S_ID = SC_S_ID
    LEFT JOIN tbl_Content_Piece ON S_ID = CP_S_ID
    INNER JOIN tbl_Region_Category ON RC_C_ID = SC_Category AND RC_Status=0
    LEFT JOIN tbl_Category ON RC_C_ID = C_ID
    WHERE SR_R_ID = " . $activeRegion['R_ID'] . " AND SC_Category IN (" . $child . ") AND S_Active = 1 AND S_Publish = 1 AND RC_Status = 0 AND RC_R_ID = " . $activeRegion['R_ID'] . "
    GROUP BY S_ID ORDER BY SR_Order ASC";
            $result_story2 = $db->get_data($sql_story2);
            $i = 0;
            $count_story2 = $result_story2->num_rows;
            if ($count_story2 > 0) {
                $text .= '<ul>';
                while ($row_story2 = $result_story2->fetch_assoc()) {
                    $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/' . $row_story['C_Name_SEO'] . '/' . clean($row_story2['S_Title']) . '/' . (isset($row_story2['S_ID']) ? $row_story2['S_ID'] : '') . '/" target="_blank">' . $row_story2['S_Title'] . '</a></li>';
                }
                $text .= '</ul>';
            }
        }
        $text .= '</li>';
    }
}
//Maps and Routes
$sql_static = "SELECT MN_ID, MN_Static_Links, MN_Order FROM tbl_Main_Navigation   
WHERE MN_R_ID = '" . $activeRegion['R_ID'] . "' AND (MN_Static_Links = 1 || MN_Static_Links = 2) GROUP BY MN_ID ORDER BY MN_Order ASC";
$result_static = $db->get_data($sql_static);
while ($row_static = $result_static->fetch_assoc()) {
    if ($row_static['MN_Static_Links'] == 1 && $activeRegion['R_Show_Maps_On_Website'] == 1) {
        $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/maps.php/" target="_blank">' . (($activeRegion['R_Map_Nav_Title'] != "") ? $activeRegion['R_Map_Nav_Title'] : 'Search by Map') . '</a></li>';
    }
    $sql_static1 = "SELECT RC_R_ID, RC_Order, RC_Feature_Photo, RC_Alt_Tag, RC_Name, RC_ID, RC_Status,  RC_Feature_Photo, RC_Introduction_Text FROM tbl_Route_Category WHERE RC_R_ID = '" . $activeRegion['R_ID'] . "' AND RC_Status = 1 ORDER BY RC_Order";
    $result_static1 = $db->get_data($sql_static1);
    $count_static1 = $result_static1->num_rows;
    if ($count_static1 > 0) {
        if ($activeRegion['R_My_Route'] == 1 && $row_static['MN_Static_Links'] == 2) {
            $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/routes/" target="_blank">' . (($activeRegion['R_Route_Title'] == "") ? "Routes" : $activeRegion['R_Route_Title']) . '</a>
    <ul>';
            while ($row_static1 = $result_static1->fetch_array()) {
                $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/routes/' . clean($row_static1['RC_Name']) . '/' . $row_static1['RC_ID'] . '/" target="_blank">' . $row_static1['RC_Name'] . '</a>';
                $sqlRoute = "SELECT IR_ID, IR_Title, IR_KML_Path, IR_Feature_Photo, IR_Start_Point, IR_End_Point, IR_Distance, IR_Surface, IR_Introduction_Text  FROM tbl_Individual_Route 
            INNER JOIN tbl_Individual_Route_Category ON IR_ID = IRC_IR_ID 
            WHERE IRC_RC_ID = '" . $db->encode_strings($row_static1['RC_ID']) . "' AND IRC_Status = 1 GROUP BY IR_ID ORDER BY IRC_Order ASC";
                $resRoute = $db->get_data($sqlRoute);
                $text .= '<ul>';
                while ($rowRoute = $resRoute->fetch_array()) {
                    $text .= '<li><a href ="https://' . $activeRegion['R_Domain'] . '/route/' . clean($rowRoute['IR_Title']) . '/' . $rowRoute['IR_ID'] . '/" target="_blank">' . $rowRoute['IR_Title'] . '</a></li>';
                }

                $text .= '</ul>
        </li>';
            }
            $text .= '</ul>
</li>';
        }
    }
}
if ($activeRegion['R_My_Trip'] == 1) {

    $text .= '<li><a href="https://' . $activeRegion['R_Domain'] . '/trip-planner/" target="_blank">' . (($activeRegion['R_Trip_Planner_Title'] == "") ? "Trip Planner" : $activeRegion['R_Trip_Planner_Title']) . '</a></li>';
}
$text .= '</ul>';

// output the HTML content
$pdf->writeHTML($text, true, false, true, false, '');
//Close and output PDF document
$pdf->Output('sitemap.pdf', 'I');
?>
