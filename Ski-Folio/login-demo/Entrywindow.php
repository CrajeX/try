<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        #title {
            text-align: center;
            padding: 20px;
            background-color: #2c7dfa;
            color: white;
        }

        #title h1 {
            margin: 0;
            font-size: 2.5em;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
            background-color: #333;
        }

        ul li {
            display: inline;
            margin: 0 15px;
        }

        ul li a {
            text-decoration: none;
            color: white;
            font-size: 1em;
            padding: 10px 15px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        ul li a:hover {
            background-color: #575757;
            border-radius: 5px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: stretch;
            flex-wrap: wrap;
            gap: 20px;
            margin: 50px auto;
            max-width: 1000px;
        }

        .card {
            background-color: white;
            border: 2px solid #2c7dfa;
            border-radius: 10px;
            margin: 15px;
            padding: 20px;
            width: 300px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            color: #2c7dfa;
            font-size: 1.8em;
        }

        .card p {
            font-size: 1em;
            color: #555;
            line-height: 1.6;
            margin: 15px 0;
        }

        .hover-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(44, 125, 250, 0.9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            flex-direction: column;
        }

        .card:hover .hover-content {
            opacity: 1;
        }

        .hover-content a {
            text-decoration: none;
            color: white;
            background-color: #1a57b8;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .hover-content a:hover {
            background-color: #0e3a8c;
        }
        .intro-section {
    text-align: center;
    margin: 50px auto;
    max-width: 90%;
    padding: 20px;
    background-color: #ffffff;
    border: 2px solid #2c7dfa;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.intro-section h2 {
    font-size: 2.5em;
    color: #2c7dfa;
    margin-bottom: 15px;
}

.intro-section p {
    font-size: 1.2em;
    line-height: 1.8;
    color: #555;
    margin: 10px 0;
}

.highlight {
    color: #1a57b8;
    font-weight: bold;
}

    </style>
</head>
<body>
    <div id="title">
        <h1>SKI-FOLIO</h1>
    </div>

    <ul>
        <li><a href="signin.php">Log-In</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <div class="intro-section">
    <h2>Welcome to <span class="highlight">Ski-Folio</span></h2>
    <p>
        Imagine a world where innovation meets opportunity—a place where skilled developers showcase their mastery, 
        and discerning employers find the perfect match for their needs. 
        <span class="highlight">Ski-Folio</span> is more than a platform; it is a bridge between talent and vision, 
        creativity and necessity.
    </p>
    <p>
        For developers, it is a canvas to demonstrate your expertise, validated by our innovative scoring system. 
        For employers, it is an exclusive gateway to discover dedicated and authentic professionals, ready to bring 
        your ideas to life. 
    </p>
    <p>
        Explore your role, embrace the journey, and unlock the potential of collaboration. Welcome to a community 
        where possibilities are endless.
    </p>
</div>

    <div class="container">
        <!-- Card for Developers -->
        <div class="card">
            <div>
                <h2>For Developers</h2>
                <p>
                    <span class="highlight">Ski-Folio</span> is your canvas to showcase innovation and expertise. 
                    Display your projects, live demos, and GitHub repositories seamlessly. Verified technical scoring 
                    highlights your skills in <span class="highlight">HTML</span>, <span class="highlight">CSS</span>, 
                    and <span class="highlight">JavaScript</span>, ensuring your talent stands out.
                </p>
            </div>
            <div class="hover-content">
                <h3>Join Now</h3>
                <a href="signup.php?userType=applicant">Join as Developer</a>
            </div>
        </div>

        <!-- Card for Employers -->
        <div class="card">
            <div>
                <h2>For Employers</h2>
                <p>
                    Discover exceptional talent with <span class="highlight">Ski-Folio</span>. 
                    Explore verified profiles showcasing live projects, badges, and technical scores. 
                    Dive deep into a pool of applicants who bring creativity and expertise to your team.
                </p>
            </div>
            <div class="hover-content">
                <h3>Join Now</h3>
                <a href="signup.php?userType=employer">Join as Employer</a>
            </div>
        </div>
    </div>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
