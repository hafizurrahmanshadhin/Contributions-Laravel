# Requirement Specification for "Contributions" App

## Project Title: Contributions

### Project Overview

Contributions is a mobile application designed to simplify the process of collecting money from groups. It aims to make group payments easy and efficient, whether it’s for group gifts, event contributions, shared expenses, or any other group activity requiring financial collaboration.

## 1. Purpose and Scope

### 1.1. Purpose

The purpose of Contributions is to provide a user-friendly platform for organizing and managing group payments. It allows users to create a collection, invite participants, track contributions, and manage funds.

### 1.2. Scope

The app will be available on both iOS and Android platforms. The core functionalities will include:

- User registration and authentication
- Collection creation
- Participant invitation
- Contribution tracking
- Funds disbursement

## 2. Functional Requirements

### 2.1. User Management

**Registration and Login:**

- Users can sign up using email, phone number, or social media accounts (Google, Facebook).
- Two-factor authentication for added security.

**Profile Management:**

- Users can update personal information such as name, email, phone number, and profile picture.
- Users can view their contribution history and current collections.

### 2.2. Collection Management

**Create a Collection:**

- Users can create a new collection by specifying the collection name, description, target amount, deadline, and optional image.

**Invite Participants:**

- Users can invite participants via WhatsApp chat, email, SMS, or social media.
- Participants receive a link to join the collection and contribute.
- Participants without the app will be directed to the website via link to make payment.

**Contribution Tracking:**

- Users can see a real-time list of contributors and their contributions.
- Notifications for contributions made, nearing deadlines, and goal completion.
- Users can share with participants collection details, e.g., number contributed out of total participants and total contribution received.

**Fund Management:**

- Option to withdraw funds to a bank account or digital wallet.

### 2.3. Payment Processing

**Payment Methods:**

- Integration with major payment gateways (PayPal, Stripe, Apple Pay, Google Pay).
- Support for credit/debit cards and bank transfers.
- Ability to make contributions from a mobile money wallet.

**Transaction History:**

- Users can view a detailed transaction history for each collection.
- Downloadable receipts for each contribution.

**Fee Deduction:**

- A percentage of between 1-3% of the total contributions in a collection will be deducted as a fee for the upkeep of the app.
- Users will be informed about the fee percentage at the time of collection creation.
- The fee will be automatically deducted before funds are transferred to the organizer.

### 2.4. Notifications

**Push Notifications:**

- Reminders for contribution deadlines and pending invitations.
- Updates on collection progress.

**Email Notifications:**

- Summary emails for new collections, contributions, and withdrawals.

### 2.5. Security and Privacy

**Data Encryption:**

- All sensitive data will be encrypted in transit and at rest.

**User Privacy:**

- Compliance with GDPR and other relevant privacy laws.
- Clear privacy policy outlining data usage and storage.

### 2.6. Admin Dashboard

**Collection Oversight:**

- Admins can view and manage all collections.
- Ability to intervene in cases of fraud or disputes.

**User Management:**

- Admins can manage user accounts, including banning and unbanning users.

**Reporting and Analytics:**

- Generate reports on app usage, collection success rates, and financial metrics.

## 3. Non-Functional Requirements

### 3.1. Performance

- The app should handle up to 10,000 concurrent users without performance degradation.
- Response time for any transaction should not exceed 2 seconds.

### 3.2. Scalability

- The system should be scalable to support a growing user base and transaction volume.

### 3.3. Reliability

- The app should have 99.9% uptime.
- Regular backups and disaster recovery mechanisms must be in place.

### 3.4. Usability

- The app should have an intuitive user interface with a focus on ease of use.
- Provide tutorials and help sections for new users.

### 3.5. Compatibility

- Support for the latest versions of iOS and Android.
- Compatible with major web browsers for the web version.

### 3.6. Maintainability

- The codebase should follow best practices and be well-documented.
- Regular updates and maintenance should be planned.

### 3.7. Security

- Regular security audits and vulnerability assessments.
- Implement strong authentication and authorization mechanisms.

## 4. Assumptions and Constraints

### 4.1. Assumptions

- Users have access to a smartphone and internet connection.
- Payment gateways are available and operational in the regions of deployment.

### 4.2. Constraints

- Completion of the project within a timeframe.

## 5. Glossary

- **User:** An individual using the Contributions app.
- **Admin:** A system administrator managing the Contributions platform.
- **Collection:** A group payment initiative created by a user.
- **Contribution:** A monetary amount added to a collection by a participant.

## Appendices

### A. Sample Screens

- Registration/Login Screen
- Home Screen
- Create Collection Screen
- Contribution Screen
- Profile Screen
- Admin Dashboard

### B. Project Timeline

- TBD: Requirement Analysis and Design
- TBD: Development
- TBD: Testing
- TBD: Deployment and Launch

### C. References

- GDPR Compliance Guidelines
- Payment Gateway Integration Documentation

This specification serves as a detailed guide for the development of the Contributions app, ensuring all stakeholders have a clear understanding of the project’s objectives, requirements, and deliverables.

--------------------

## Discussions/Actions from Catchup this morning – 24/10/24

**Action: Currency Conversion**
Details: Agreement to maintain one main currency (the $) throughout app usage.

**Action: Withdrawal**
Details: Withdrawal of funds, functionality of the App will require the organisers to send a request to admin for withdrawal. Upon receipt of request, admin will transfer total funds minus fee to the bank account provided, via admin portal.

**Action: Closing of Pot/Deadline of collection**
Details: Contributors won’t be able to contribute to the pot after the deadline date. Organisers can extend deadlines as much as they want before the actual selected date.

**Action: Invite to Contribute Button**
Details: Invite to contribute button to hover at the bottom of the screen. It gets hidden when the list of contributors extends past the phone screen, which you will have to scroll all the way down to see under the list of contributors.

............................................................

## [Postman Collection](https://documenter.getpostman.com/view/32086283/2sB3BBpXKt)

## [Live Link Client Server](https://contributionspayment.com)

## [Live Link Softvence Server](https://gaddoltd.reigeeky.com)
