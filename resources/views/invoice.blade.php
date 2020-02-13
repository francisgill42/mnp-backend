<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>United Cool Trading Tax Invoice</title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 14px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="6">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="https://orangeroomdigital.com/mnp_dev/public/uploads/invoice/united-cool-logo.jpg" style="width:100%; max-width:200px;">
                            </td>
                            
                            <td>
                                <img src="https://orangeroomdigital.com/mnp_dev/public/uploads/invoice/movenpick-logo.jpg" style="width:100%; max-width:200px;float:right;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><h1 style="text-align: center;">TAX INVOICE</h1></td>
                        </tr>
                        <tr>
                           
                            <td class="title" style="width:50%;">                                
                                <p style="text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 20px; margin-top: 0;padding-left:10px;font-weight:700;">United Cool General Trading</p>
                                <p style="text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 20px; margin-top: 0;padding-left:10px;">VAT #: <?php echo $order->ntn; ?></p>
                                <p style="text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 20px; margin-top: 0;padding-left:10px;">(MÖVENPICK Ice Cream)</p>
                                <p style="text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 20px; margin-top: 0;padding-left:10px;">Dubai, U.A.E.</p>
                                <p style="text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 20px; margin-top: 0;padding-left:10px;">P.O. Box #: 232180</p>
                            </td>
                            <td>
                                <h3 style="padding:0;margin:0; text-align:left;padding-left:15px;">Bank Account Details</h3>
                                <table style="text-align:left;text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 12px; margin-top: 0;padding-left:10px;text-align: left;">
                                    <tr style="text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 20px; margin-top: 0;padding-left:10px;text-align: left;">
                                        <td style="text-align: left; padding-bottom:3px;"><strong>Account Name:</strong></td>
                                        <td style="text-align: left; padding-bottom:3px;">United Cool General Trading LLC</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; padding-bottom:3px;"><strong>Bank: </strong></td>
                                        <td style="text-align: left; padding-bottom:3px;">Emirates NBD </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; padding-bottom:3px;"><strong>Branch Name:</strong></td>
                                        <td style="text-align: left; padding-bottom:3px;">Jumeirah Branch </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; padding-bottom:3px;"><strong>Branch Code:</strong></td>
                                        <td style="text-align: left; padding-bottom:3px;">1213 </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; padding-bottom:3px;"><strong>Account Number: </strong></td>
                                        <td style="text-align: left; padding-bottom:3px;">1015681013401</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; padding-bottom:3px;"><strong>IBAN Number:</strong></td>
                                        <td style="text-align: left; padding-bottom:3px;">AE840260001015681013401</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; padding-bottom:3px;"><strong>Swift Code: </strong></td>
                                        <td style="text-align: left; padding-bottom:3px;">EBILAED </td>
                                    </tr>
                                </table>
                               
                            </td>
                           
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td style="width:50%;"> 
                            <strong>Invoice #:</strong><?php echo $order->prefix.''.$order->invoice_number; ?><br>
                            <strong>Delivery Date:</strong> <?php echo date('F d, Y',strtotime($order->delivery_date)); ?><br>
                            <?php if(isset($order->payment_due_date)){ ?>
                            <strong>Payment Due Date:</strong> <?php echo date('F d, Y',strtotime($order->payment_due_date)); ?>
                            <?php } ?>
                            </td>
                            <td> 
                                 
                            <td style="width:50%;">
                                   
                                    <table style="text-align:left;text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 12px; margin-top: 0;padding-left:10px;text-align: left;">
                                            <tr style="text-transform:lowercase ; text-transform:capitalize; font-size: 14px; margin-bottom: 0;line-height: 20px; margin-top: 0;padding-left:10px;text-align: left;">
                                                <td style="text-align: left; padding-bottom:3px;"><strong>Company:</strong></td>
                                                <td style="text-align: left; padding-bottom:3px;"><?php echo $order->company_name ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-bottom:3px;"><strong>Trade Name: </strong></td>
                                                <td style="text-align: left; padding-bottom:3px;"><?php echo $order->trade_name ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-bottom:3px;"><strong>Contact Person: </strong></td>
                                                <td style="text-align: left; padding-bottom:3px;"><?php echo $order->contact_person_name ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-bottom:3px;"><strong>Phone:</strong></td>
                                                <td style="text-align: left; padding-bottom:3px;"><?php echo $order->mobile_number ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-bottom:3px;"><strong>Email Address: </strong></td>
                                                <td style="text-align: left; padding-bottom:3px;"><?php echo $order->email ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-bottom:3px;"><strong>Address:</strong></td>
                                                <td style="text-align: left; padding-bottom:3px;"><?php echo $order->address ?></td>
                                            </tr>
                                           
                                           
                                        </table>
                                 
                               
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <!-- <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                          
                            
                            <td>
                                <h4>Customer:</h4>
                                Resorts Supplies General Trading<br>
                                Contact Details:<br>
                                Name: Shella<br/>
                                Phome: +971 52 911 2566
                                Email Address: shella@Resortssupplies.ae
                                Address: Sheik Mohammed Bin Rashed Blvd, Downtown Dubai
                            </td>
                            <td>
                                  
                                </td>
                        </tr>
                    </table>
                </td>
            </tr> -->
            
          
            
          
            
            <tr class="heading">
                <td style="width:5%;text-align: left;">
                        Item Code
                </td>
                
                <td style="width:20%;text-align: left;">
                        Item Description
                </td>
                <td style="width:10%;text-align: center;">
                        Quantity
                </td>
                
                <td  style="width:10%;text-align: center;">
                        Unit Price
                </td>
                <td  style="width:10%;text-align: right;">
                        VAT 5%
                </td>
                <td  style="width:10%;text-align: right;">
                        Amount (AED)
                </td>
            </tr>
            <?php
            foreach($order->products as $pro){
            ?>
            <tr class="item">
                    <td style="width:5%;text-align: left;">
                            <?php echo $pro->order_item_id; ?>
                    </td>
                    
                    <td style="width:20%;text-align: left;">
                    <?php echo $pro->product_title; ?>
                    </td>
                    <td style="width:10%;text-align: center;">
                    <?php echo $pro->product_quantity; ?>
                    </td>
                    
                    <td  style="width:10%;text-align: center;">
                    <?php echo $pro->product_price; ?>
                    </td>
                    <td  style="width:10%;text-align: right;">
                    <?php echo ($pro->product_price/100)*5; ?>
                    </td>
                    <td  style="width:10%;text-align: right;">
                    <?php echo ($pro->product_price*$pro->product_quantity); ?>
                    </td>
            </tr>
        <?php 
        } 
        ?>       

<tr class="total" style="text-align:right;">
                <td colspan="3"></td>
                
                <td colspan="3" style="font-weight:bold;border-top: 2px solid #eee;">
                   Sub-Total: AED <?php echo number_format($order->order_gross,2); ?>
                </td>
            </tr>
            <tr class="total" style="text-align:right;">
                <td colspan="3"></td>
                
                <td colspan="3" style="font-weight:bold;border-top: 2px solid #eee;">
                   VAT: AED <?php echo number_format($order->order_tax,2); ?>
                </td>
            </tr>
            <?php if($order->discounted_price){ ?>
            <tr class="total" style="text-align:right;">
                <td colspan="3"></td>
                
                <td colspan="3" style="font-weight:bold;border-top: 2px solid #eee;">
                   Discount: AED <?php echo number_format($order->discounted_price,2); ?>
                </td>
            </tr>
            <?php } ?>
            <tr class="total" style="text-align:right;">
                <td colspan="3"></td>
                
                <td colspan="3" style="font-weight:bold;border-top: 2px solid #eee;">
                   Grand Total: AED <?php echo number_format($order->order_total,2); ?>
                </td>
            </tr>
        </table>
        Note: This is computer generated invoice no need to sign or stamp.<br />
        Thank you for your business.
        <div style="font-size: 10px; text-align: center; padding-top: 20px; border-top:1px solid #000;">United Cool General Trading LLC, Dubai - UAE, P.O. Box 232180, Paid Up Capital AED 300,000.00, Tax Reg. Number 100375833900003</div>
    </div>

   
</body>
</html>
