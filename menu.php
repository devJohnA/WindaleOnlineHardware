<style>
.features_items .col-sm-4 {
    width: 16.666%; /* 6 columns by default */
    padding: 5px;
    float: left; /* Ensure float behavior */
}

.product-image-wrapper {
    height: 300px;
    margin-bottom: 15px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 4px;
    transition: box-shadow 0.3s ease;
    width: 100%;
}

/* Clear fix for floating elements */
.features_items::after {
    content: "";
    display: table;
    clear: both;
}

/* Ensure minimum width for product cards */
.productinfo {
    min-width: 150px; /* Minimum width for product cards */
    padding: 8px;
    text-align: center;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-image-wrapper:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.single-products {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.productinfo img {
    height: 140px;
    width: 100%;
    object-fit: contain;
    margin-bottom: 8px;
}

.productinfo h5 {
    font-size: 14px;
    margin: 5px 0;
    color: #fd2323;
}

.productinfo p {
    font-size: 12px;
    margin: 3px 0;
    line-height: 1.3;
}

.star-rating {
    font-size: 12px;
    margin: 5px 0;
    color: #fd2323;
}

.star-rating span {
    margin: 0 1px;
}

.add-to-cart {
    font-size: 12px;
    padding: 5px 10px;
    margin-top: auto;
}

/* Responsive breakpoints */
@media (max-width: 1200px) {
    .features_items .col-sm-4 {
        width: 20%; /* 5 columns */
    }
}

@media (max-width: 992px) {
    .features_items .col-sm-4 {
        width: 25%; /* 4 columns */
    }
}

@media (max-width: 768px) {
    .features_items .col-sm-4 {
        width: 33.333%; /* 3 columns */
    }
}

@media (max-width: 576px) {
    .features_items .col-sm-4 {
        width: 50%; /* 2 columns */
        float: left;
    }
    .product-image-wrapper {
        height: 250px; /* Slightly reduce height for mobile */
    }
}

/* Extra small devices fix */
@media (max-width: 400px) {
    .features_items .col-sm-4 {
        width: 50%; /* Maintain 2 columns */
        padding: 3px; /* Reduce padding */
    }
    .product-image-wrapper {
        height: 220px; /* Further reduce height */
    }
    .productinfo img {
        height: 100px; /* Smaller images */
    }
    .productinfo h5 {
        font-size: 12px; /* Smaller text */
    }
    .productinfo p {
        font-size: 11px;
    }
}
</style>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 padding-right">
                <div class="features_items">
                    <h2 class="title text-center">Products</h2>
                    <?php
             if(isset($_POST['search'])) { 
                $query = "SELECT *, (SELECT AVG(RATING) FROM tblcustomerreview WHERE PROID = p.PROID) as avg_rating, (SELECT COUNT(*) FROM tblcustomerreview WHERE PROID = p.PROID) as review_count 
                          FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                          WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND PROQTY>0 
                          AND ( `CATEGORIES` LIKE '%{$_POST['search']}%' OR `PRODESC` LIKE '%{$_POST['search']}%' or `PROQTY` LIKE '%{$_POST['search']}%' or `PROPRICE` LIKE '%{$_POST['search']}%')";
              } elseif(isset($_GET['category'])) {
                $query = "SELECT *, (SELECT AVG(RATING) FROM tblcustomerreview WHERE PROID = p.PROID) as avg_rating, (SELECT COUNT(*) FROM tblcustomerreview WHERE PROID = p.PROID) as review_count 
                          FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                          WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND PROQTY>0 AND CATEGORIES='{$_GET['category']}'";
              } else {
                $query = "SELECT *, (SELECT AVG(RATING) FROM tblcustomerreview WHERE PROID = p.PROID) as avg_rating, (SELECT COUNT(*) FROM tblcustomerreview WHERE PROID = p.PROID) as review_count 
                          FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                          WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND PROQTY>0";
              }

            $mydb->setQuery($query);
            $res = $mydb->executeQuery();
            $maxrow = $mydb->num_rows($res);

            if ($maxrow > 0) { 
            $cur = $mydb->loadResultList();
           
            foreach ($cur as $result) { 
            ?>
                    <form method="POST" action="cart/controller.php?action=add">
                        <input type="hidden" name="PROPRICE" value="<?php echo $result->PROPRICE; ?>">
                        <input type="hidden" id="PROQTY" name="PROQTY" value="<?php echo $result->PROQTY; ?>">
                        <input type="hidden" name="PROID" value="<?php echo $result->PROID; ?>">
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <a href="index.php?q=single-item&id=<?php echo $result->PROID; ?>"> 
                                            <img src="<?php echo web_root.'admin/products/'. $result->IMAGES; ?>" alt="" />
                                        </a> 
                                        <h5>&#8369; <?php echo $result->PRODISPRICE; ?></h5>
                                        <p>Quantity: <em><?php echo $result->PROQTY; ?></em></p>
                                        <p><em><?php echo $result->PRODESC; ?></em></p>
                                        
                                        <!-- Star Rating Display -->
                                        <div class="star-rating">
    <?php
    // Handle NULL rating by defaulting to 0
    $avg_rating = is_null($result->avg_rating) ? 0 : (float)$result->avg_rating;
    $review_count = (int)$result->review_count;
    
    $full_stars = floor($avg_rating);
    $half_star = ($avg_rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    // Display stars
    for ($i = 1; $i <= $full_stars; $i++) {
        echo '<span class="fa fa-star"></span>';
    }
    if ($half_star) {
        echo '<span class="fa fa-star-half-alt"></span>';
    }
    for ($i = 1; $i <= $empty_stars; $i++) {
        echo '<span class="far fa-star"></span>';
    }
    
    // Format display with proper handling of NULL/zero cases
    $rating_display = $review_count > 0 ? 
        number_format($avg_rating, 1) : 
        '0.0';
    ?>
    <span>(<?php echo $rating_display; ?> / <?php echo $review_count; ?> reviews)</span>
</div>
                                        
                                        <button type="submit" name="btnorder" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php  
                    } 
                } else { 
                    echo '<h1>No Products Available</h1>';
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</section>