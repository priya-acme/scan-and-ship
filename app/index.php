<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 ?>
<?php include 'header.php' ?>
<div id="content">
<table border="1">
        <tr>
            <th>
              Shop 
            </th>
            <th>
              Shop Preference
            </th>
        </tr>
       </table>

<?php include 'footer.php' ?>