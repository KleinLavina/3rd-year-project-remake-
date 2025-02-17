<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cracken Tech</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        footer {
            background-color: #031708;
            color: white;
            padding: 40px 0;
            font-family: 'Arial', sans-serif;
        }

        .footer-container {
            width: 85%;
            margin: 0 auto;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        .footer-column {
            flex: 1;
            min-width: 250px;
            margin: 10px;
        }

        .footer-column h3 {
            font-size: 22px;
            margin-bottom: 15px;
            font-weight: bold;
            color: rgb(22, 195, 201);
        }

        .footer-column p, .footer-column ul {
            font-size: 14px;
            line-height: 1.7;
        }

        .footer-column a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .footer-column a:hover {
            color: rgb(22, 195, 201);
            transform: translateX(5px);
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-column ul li {
            margin: 8px 0;
        }

        .footer-column ul li a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-column ul li a:hover {
            color: rgb(22, 195, 201);
        }

        .footer-column p {
            font-size: 14px;
            color: #bbb;
        }

        /* Add some borders for distinction */
        .footer-column {
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            padding-right: 15px;
        }

        .footer-column:last-child {
            border-right: none;
        }

        /* Make sure text is responsive */
        @media screen and (max-width: 768px) {
            .footer-row {
                flex-direction: column;
                align-items: center;
            }

            .footer-column {
                margin-bottom: 20px;
                border-right: none;
            }

            .footer-column h3 {
                font-size: 20px;
            }

            .footer-column p, .footer-column ul {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <footer>
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-column">
                    <h3>Cracken Tech</h3>
                    <p>At Cracken Tech, we're dedicated to making the world a better place through innovative technology. Join us as we create solutions that impact lives globally.</p>
                    <p><strong>Klein Lavina</strong>, CEO</p>
                </div>

                <div class="footer-column">
                    <h3>Our Products</h3>
                    <ul>
                        <li><a href="#">Tech Solutions</a></li>
                        <li><a href="#">Innovative Apps</a></li>
                        <li><a href="#">AI Development</a></li>
                        <li><a href="#">Sustainability Tech</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Useful Links</h3>
                    <ul>
                        <li><a href="#">Your Account</a></li>
                        <li><a href="#">Become an Affiliate</a></li>
                        <li><a href="#">Shipping Rates</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Contact</h3>
                    <p>123 Tech Avenue, Tag- os Matalom, USA</p>
                    <p>Email: contact@crackentech.com</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
