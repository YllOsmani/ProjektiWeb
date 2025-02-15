<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoes Store</title>
    <link rel="stylesheet" href="b.css">
    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
            function showPage(page) {
                
                var sections = document.querySelectorAll('section');
                sections.forEach(function(section) {
                    section.style.display = 'none';
                });

               
                var selectedPage = document.querySelector('.' + page);
                if (selectedPage) {
                    selectedPage.style.display = 'block';
                }
            }

            document.querySelector('.home-link').addEventListener('click', function(e) {
                e.preventDefault(); 
                showPage('home'); 
            });

            document.querySelector('.news-link').addEventListener('click', function(e) {
                e.preventDefault(); 
                showPage('news');
            });

            
            showPage('home');
        });
    </script>
</head>
<body>

   
<?php
require 'requires/header.php';
?>

   
    <section class="home">
        <div class="home-content">
            <h2>Welcome to Shoes Store</h2>
            <p>Your perfect pair is just a click away.</p>
            <blockquote>"The journey of a thousand steps begins with the right pair of shoes."</blockquote>
            <blockquote>"Step into comfort and style with our exclusive collection."</blockquote>
            <a href="products.php" class="cta-button">Explore Our Collection</a>
        </div>
    </section>

   
    <section class="news" style="display: none;">
        <h2>Latest News & Offers</h2>
        <div class="news-item">
            <img src="https://www.superkicks.in/cdn/shop/files/2_2fab76a7-8607-4ed4-8221-34353e3e60fe.jpg?v=1705402289&width=800" alt="Nike Sneakers">
            <div class="news-content">
                <h3>New Nike Sneakers Collection</h3>
                <p>Check out the latest Nike collection! Get your perfect pair of sneakers today. We have a wide range of options available for every style.</p>
                <span class="offer">Limited Offer: 20% off</span>
            </div>
        </div>

        <div class="news-item">
            <img src="https://media.jdsports.com/i/jdsports/FN6843_100_P1?$default$&w=671&&h=671&bg=rgb(237,237,237)" alt="JD Sports Sneakers">
            <div class="news-content">
                <h3>Exclusive JD Sports Sneakers</h3>
                <p>Grab your exclusive JD sneakers now with a huge discount. Only available online, hurry before they're gone!</p>
                <span class="offer">Buy 1 Get 1 Free</span>
            </div>
        </div>

        <div class="news-item">
            <img src="https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/254ba503-d11a-49ed-942f-221867cea9d2/NIKE+AIR+MAX+1.png" alt="Nike Air Max">
            <div class="news-content">
                <h3>Air Max 1 - Iconic Style</h3>
                <p>The iconic Nike Air Max 1 is back in stock! Perfect for style and comfort. Available in various colors and sizes.</p>
                <span class="offer">10% off for the first 100 customers</span>
            </div>
        </div>

        <div class="news-item">
            <img src="https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/a4ca0cdc-6d51-4d6d-93d8-43a74ff848d8/AIR+MAX+2013.png" alt="Nike Air Max 2013">
            <div class="news-content">
                <h3>New Nike Air Max 2013</h3>
                <p>The newest addition to the Air Max family. Stylish and ultra-comfortable for everyday wear, designed to keep your feet comfortable all day.</p>
                <span class="offer">Free Shipping on all orders!</span>
            </div>
        </div>

        
        <div class="news-item">
            <img src="https://www.urbanathletics.com.ph/cdn/shop/products/CU4826-100-A.jpg?v=1667377321" alt="Nike Air Force 1">
            <div class="news-content">
                <h3>Air Force 1 - Classic Design</h3>
                <p>Get the timeless Air Force 1 sneakers, a classic design for every occasion. A must-have for sneaker enthusiasts!</p>
                <span class="offer">15% off this week only</span>
            </div>
        </div>

        <div class="news-item">
            <img src="https://www.nike.sa/dw/image/v2/BDVB_PRD/on/demandware.static/-/Sites-akeneo-master-catalog/default/dw96b95243/nk/ac0/4/7/f/4/f/ac047f4f_ce27_4b18_94c4_1d1e77557f09.jpg?sw=700&sh=700&sm=fit&q=100&strip=false" alt="Nike Air Max 90">
            <div class="news-content">
                <h3>Air Max 90 - Enhanced Comfort</h3>
                <p>Step up your game with the Nike Air Max 90, designed for optimal comfort and style. A perfect choice for everyday wear.</p>
                <span class="offer">Get 10% off with code AIRMAX10</span>
            </div>
        </div>

        <div class="news-item">
            <img src="https://www.superkicks.in/cdn/shop/files/1_cc492b95-4496-4e37-8000-2a16e8962e3b.jpg?v=1721729610&width=533" alt="Adidas Ultraboost">
            <div class="news-content">
                <h3>Adidas Ultraboost - Maximum Comfort</h3>
                <p>Experience the best in comfort with the Adidas Ultraboost, engineered to provide exceptional energy return and support.</p>
                <span class="offer">20% off on all Ultraboost models</span>
            </div>
        </div>

        <div class="news-item">
            <img src="https://www.shoepalace.com/cdn/shop/products/096b8f03f3bea3b2edb4d5e2bc96b93c_2048x2048.jpg?v=1694641370&title=nike-bq5068-001-air-vapormax-plus-mens-running-shoes-black" alt="Nike Air Vapormax Plus">
            <div class="news-content">
                <h3>Nike Air Vapormax Plus - Running Shoes</h3>
                <p>The Nike Air Vapormax Plus running shoes combine modern style with top-tier performance. Perfect for serious runners.</p>
                <span class="offer">Buy now and get a free water
