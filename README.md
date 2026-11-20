# Book Shipping & Notification Demo

A synthetic shipping workflow demonstrating shipment creation, status progression, REST APIs, carrier abstraction, and notification-ready architecture.

## Features
- Create demo shipments
- Track shipment status
- Advance delivery workflow
- No external API keys or real customers

## Architecture
API → Shipment service model → carrier/status abstraction → JSON response

## Run
```powershell
npm install
npm start
```

Default port: `4001`.
