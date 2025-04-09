# Charity Association Manager

A comprehensive web application for managing charitable association activities, members, partners, and public interactions. This project was developed as part of the TDW 2CS course at École Supérieure d'Informatique for the 2024/2025 academic year.

## 🌟 Features

### Public Interface
- **Dynamic Homepage**: Features a rotating slideshow of news and partners, intuitive navigation, and content sections for activities and announcements
- **Partner Catalog**: Organized by categories (Hotels, Clinics, Schools, Travel Agencies) with filtering capabilities
- **Member Registration**: Complete registration system with photo upload, ID verification, and payment receipt processing
- **Electronic Member Cards**: Auto-generated cards with QR codes for discount verification
- **Discount & Benefits System**: Various membership tiers with different privilege levels
- **Donation & Volunteer Management**: Tracking system for donations and volunteer sign-ups
- **Help Request System**: Form-based help request system with document upload capabilities

### Administrative Interface
- **Partner Management**: Complete CRUD operations for partners with discount configuration
- **Member Management**: Member approval system with filtering and sorting options
- **Donation & Volunteer Administration**: Validation and statistical tracking
- **Notification System**: Scheduled announcements and reminders
- **Subscription Management**: Payment tracking and automatic receipt generation
- **Application Settings**: Customizable appearance settings and security controls

## 💻 Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript, jQuery, AJAX
- **Backend**: PHP (Object-Oriented with MVC architecture)
- **Database**: MySQL
- **Additional Tools**: QR Code generation library

## 🏗️ Project Structure

The application follows the MVC (Model-View-Controller) architecture:

```
charity-association-manager/
├── app/
│   ├── controllers/    # Application logic
│   ├── models/         # Database interactions
│   └── views/          # Presentation templates
|   |__ core/           # Config files and routing
├── public/
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   ├── images/         # Image resources
│   └── uploads/        # User uploaded content
├── config/             # Configuration files
└── index.php           # Entry point
```

## 🚀 Installation

1. Clone this repository:
   ```
   git clone https://github.com/aminetech26/charity-association-manager.git
   ```

2. Create a MySQL database and import the provided SQL schema:
   ```
   mysql -u username -p database_name < database/TDW.sql
   ```

3. Configure database connection in `config/database.php`

4. Set up a web server (Apache/Nginx) to point to the project directory

5. Open in your browser and register as a new member or admin

## 🖥️ Screenshots


## 🔜 Future Improvements

- Mobile application integration
- Payment gateway for direct online donations
- Enhanced reporting and analytics dashboard
- Internationalization support

## 📝 Course Information

This project was developed for the TDW 2CS module at École Supérieure d'Informatique for the 2024/2025 academic year.
