# Power-And-Go Electric Vehicle Fleet Management

## Overview
An comprehensive electric vehicle fleet management system with dashboard and admin interface for managing vehicle fleets, rentals, and user permissions.

## Key Features
- 🚗 Vehicle Fleet Management
- 📊 Detailed Vehicle Equipment Tracking
- 🔄 Dynamic Category Grouping
- 📝 Rental & Issue Management
- 👥 User & Staff Administration
- 💰 Automated Billing System
- 🎫 Customer Subscription Management
- 📈 Real-time Statistics & Reports

## Technical Stack
- Backend: RESTful API
- Frontend: React Dashboard
- Database: MySQL
- Containerization: Docker

## Installation Guide

### Prerequisites
- Docker
- Git
- Linux-based environment (recommended)

### Quick Start
```bash
# Clone the repository
git clone https://github.com/Bari1192/Power-And-Go
cd Power-And-Go

# Initialize the project
sh start.sh
```

### Local Access Points
- Backend: http://backend.vm1.test
- Frontend: http://frontend.vm1.test
- JSON Server: http://jsonserver.vm1.test
- API Documentation: http://swagger.vm1.test
- Documentation: http://docs.vm1.test

## Testing

### Running Tests
```bash
# Access the backend container
docker compose exec backend fish

# Run tests
php artisan test
```

### Reset & Rebuild
```bash
sh start.sh
```

## Development Guidelines

### Data Generation
- Modify factories in the `backend/database/factories` directory
- Adjust seeder quantities in `backend/database/seeders`
- Always verify relationships before modifying data structures

## Contributors

### [@rcsnjszg](https://github.com/rcsnjszg)
- Core backend functionality
- System architecture
- Debug support

### [@ignaczdominik](https://github.com/ignaczdominik)
- Frontend development
- UI/UX implementation
- System optimization

## License
This project is proprietary software. All rights reserved.