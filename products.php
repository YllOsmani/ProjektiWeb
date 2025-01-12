<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Sport</title>
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="b.css">
    <link rel="shortcut icon" href="images/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
<?php
require 'requires/header.php';
require 'requires/conn.php';

// Initialize an empty array to store products
$products = [];

// SQL query to fetch data from the products table
$sql = "SELECT id, title, description, price, image FROM products";
$result = $conn->query($sql);

// Check if there are any records
if ($result->num_rows > 0) {
    // Loop through the results and store them in the $products array
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image' => $row['image']
        ];
    }
} else {
    // Optionally handle the case when no products are found
    $products = [];
}

// Close the connection
$conn->close();
?>
    <section>
    

        <div class="main" id="Home">
            <div class="main_content">
                <div class="main_text">
                    <h1>NIKE<br><span>Collection</span></h1>
                    <p>
                        Our collection of high-fashioned, high-quality and comfortable shoes, staying up to date with style every step of the way. Come and check it out and be mesmerized.
                    </p>
                </div>
            </div>
            <div class="social_icon">
                <i class="fa-brands fa-facebook-f"></i>
                <i class="fa-brands fa-twitter"></i>
                <i class="fa-brands fa-instagram"></i>
                <i class="fa-brands fa-linkedin-in"></i>
            </div>
            
        </div>
    
     </section>
     
     <!--Products-->
    <div class="products" id="Products">
        

        <div class="box">
        <?php
// Loop through the $products array and generate the HTML
            foreach ($products as $product) {
                echo '<div class="card">';

                // Small card with icons
                echo '   <div class="small_card">';
                echo '       <i class="fa-solid fa-heart"></i>';
                echo '       <i class="fa-solid fa-share"></i>';
                echo '   </div>';

                // Product image
                echo '   <div class="image">';
                echo '       <img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['title']) . '">';
                echo '   </div>';

                // Product text details
                echo '   <div class="products_text">';
                echo '       <h2>' . htmlspecialchars($product['title']) . '</h2>';
                echo '       <p>' . htmlspecialchars($product['description']) . '</p>';
                echo '       <h3>&euro;' . number_format($product['price'], 2) . '</h3>';

                // Stars (example static, can be dynamic based on a rating field)
                echo '       <div class="products_star">';
                echo '           <i class="fa-regular fa-star"></i>';
                echo '           <i class="fa-regular fa-star"></i>';
                echo '           <i class="fa-regular fa-star"></i>';
                echo '           <i class="fa-regular fa-star"></i>';
                echo '           <i class="fa-regular fa-star"></i>';
                echo '       </div>';

                // Add to Cart button
                echo '       <a href="#" class="btn">Add To Cart</a>';
                echo '   </div>';

                echo '</div>';
            }?>




        </div>
    </div>
    
    <!--Services-->

    <div class="services" id="Services">
        

        <div class="services_cards">
            <div class="services_box">
                <i class="fa-solid fa-truck-fast"></i>
                <h3>Fast Delivery</h3>
                <p>
                    One of our many advantages include super fast delivery everywhere within the country
                </p>
            </div>


            <div class="services_box">
                <i class="fa-solid fa-rotate-left"></i>
                <h3>10 Days Replacement</h3>
                <p>
                    Product return with no problems and money returned.
                </p>
            </div>

            <div class="services_box">
                <i class="fa-solid fa-headset"></i>
                <h3>24 x 7 Support</h3>
                <p>
                    Offering solutions whenever you may feel like it.
                </p>
            </div>

        </div>
          
    </div>


    <footer>
        <div class="footer_main">
            <div class="tag">
                <h1>Contact</h1>
                <a href="#"><i class="fa-solid fa-house"></i>123/Ferizaj/Kosovo</a>
                <a href="#"><i class="fa-solid fa-phone"></i>+383 898 023</a>
                <a href="mailto:yo45318@ubt-uni.net"><i class="fa-solid fa-envelope"></i>GoSport@gmail.com</a>
            </div>

            <div class="tag">
                <h1>Get Help</h1>
                <a href="#" class="center">FAQ</a>
                <a href="#" class="center">Shipping</a>
                <a href="#" class="center">Returns</a>
                <a href="#" class="center">Payment Options</a>
            </div>

            <div class="tag">
                <h1>Our Stores</h1>
                <a href="#" class="center">Kosovo</a>
                <a href="#" class="center">Albania</a>
                <a href="#" class="center">Macedonia</a>
                <a href="#" class="center">Montenegro</a>
            </div>

            <div class="tag">
                <h1>Follow Us</h1>
                <div class="social_link">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://x.com/?lang=en"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>                    
                </div>
            </div>

            <div class="tag">
                <h1>Newsletter</h1>
                <div class="search_bar">
                    <input type="text" placeholder="You email id here">
                    <button type="submit">Subscribe</button>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
