Yob Yong Ordering System
Final Year Project
🚀 Quick Start
1. Clone the Repository
git clone https://github.com/gibletchaser/Final-Year-Project.git
cd Final-Year-Project

2. Setup Environment
Copy the example environment file:
cp .env.example .env
Edit .env file with your configuration:
STRIPE_PUBLIC_KEY=pk_test_your_public_key_here
STRIPE_SECRET_KEY=sk_test_your_secret_key_here
APP_ENV=development
DEBUG_MODE=true

3. Import Database
mysql -u root -p yobyong < yobyong.sql

4. Install Dependencies
composer require stripe/stripe-php
Or download manually:
mkdir -p assets/vendor
cd assets/vendor
git clone https://github.com/stripe/stripe-php.git stripe

5. Configure Web Server
Point web server (Apache/Nginx) to project root
Ensure PHP 7.4+ is installed
Enable MySQL extension in PHP
🔧 Configuration
Stripe Setup

Create a Stripe account at https://stripe.com
Get test API keys from Dashboard → Developers → API keys
Add keys to .env file
Database Setup

Create MySQL database named yobyong
Import SQL file: yobyong.sql
Update database credentials in .env
👥 User Accounts
Pre-configured Accounts:

Admin — admin@example.com / password
Staff — staff@example.com / password
Customer — customer@example.com / password
Guest — No login required, view only
💳 Payment Testing
Test Cards (Stripe Sandbox):

4242 4242 4242 4242 — Successful payment
4000 0000 0000 9995 — Declined payment
4000 0027 6000 3184 — Requires authentication
Expiry: Any future date | CVC: Any 3 digits | ZIP: Any 5 digits
