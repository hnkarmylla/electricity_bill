<?php
$block1 = $block2 = $block3 = $block4 = $block5 = "";
$charge1 = $charge2 = $charge3 = $charge4 = $charge5 = 0;
$totalkWh = $totalCharge = $sst = $totalBill = 0;
$error = "";
$showBill = false;

if (isset($_POST['calculate'])) {

    $block1_raw = trim($_POST['block1']);
    $block2_raw = trim($_POST['block2']);
    $block3_raw = trim($_POST['block3']);
    $block4_raw = trim($_POST['block4']);
    $block5_raw = trim($_POST['block5']);

    // Check for non-numeric inputs
    if (($block1_raw !== "" && !is_numeric($block1_raw)) ||
        ($block2_raw !== "" && !is_numeric($block2_raw)) ||
        ($block3_raw !== "" && !is_numeric($block3_raw)) ||
        ($block4_raw !== "" && !is_numeric($block4_raw)) ||
        ($block5_raw !== "" && !is_numeric($block5_raw))
    ) {

        $error = "Please enter only numbers";
    } else {
        $block1 = $block1_raw !== "" ? (float)$block1_raw : 0;
        $block2 = $block2_raw !== "" ? (float)$block2_raw : 0;
        $block3 = $block3_raw !== "" ? (float)$block3_raw : 0;
        $block4 = $block4_raw !== "" ? (float)$block4_raw : 0;
        $block5 = $block5_raw !== "" ? (float)$block5_raw : 0;

        // Validation rules
        if ($block1 < 0 || $block2 < 0 || $block3 < 0 || $block4 < 0 || $block5 < 0) {
            $error = "kWH cannot be a negative number. Try again";
        } else if ($block1 > 200) {
            $error = "Please enter less than number 200kWh";
        } else if ($block2 > 100) {
            $error = "Please enter less than number 100kWh";
        } else if ($block3 > 300) {
            $error = "Please enter less than number 300kWh";
        } else if ($block4 > 300) {
            $error = "Please enter less than number 300kWh";
        } else if (($block1 + $block2 + $block3 + $block4 + $block5) == 0) {
            $error = "Please enter your electricity usage";
        } else {
            // Calculate charge for every block
            $charge1 = $block1 * 0.218;
            $charge2 = $block2 * 0.344;
            $charge3 = $block3 * 0.516;
            $charge4 = $block4 * 0.546;
            $charge5 = $block5 * 0.571;

            $totalkWh = $block1 + $block2 + $block3 + $block4 + $block5;

            // Sum ALL charges for total consumption
            $totalCharge = $charge1 + $charge2 + $charge3 + $charge4 + $charge5;

            // Minimum charge check
            if ($totalCharge < 300 && $totalkWh > 0) {
                $totalCharge = 300;
            }

            // SST 6% calculated on total consumption if total kWh > 600
            if ($totalkWh > 600) {
                $sst = $totalCharge * 0.06;
            } else {
                $sst = 0;
            }

            $totalBill = $totalCharge + $sst;
            $showBill = true;
        }
    }
}
