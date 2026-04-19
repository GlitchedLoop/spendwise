# SpendWise — Personal Finance Dashboard

SpendWise is a full-stack Personal Finance Management System built using PHP + MySQL for AP project submission.

It helps users manage income, expenses, budgets, analytics, CSV bank statement imports, financial insights, expense prediction, and subscription detection through a modern dashboard interface.

---

# Tech Stack

## Frontend
- HTML5
- CSS3
- JavaScript (Vanilla JS)
- Chart.js

## Backend
- Core PHP

## Database
- MySQL

## Server Environment
- XAMPP / Apache / Localhost

---

# Core Features

## Authentication
- User Signup
- User Login
- Secure Logout
- Password Hashing using `password_hash()`
- Password Verification using `password_verify()`
- Session Management
- Protected Routes

---

## Dashboard
- Total Balance
- Monthly Income
- Monthly Expenses
- Budget Overview
- Recent Transactions
- Smart Alerts
- Quick Financial Summary

---

## Transaction Management
- Add Transaction
- Edit Transaction
- Delete Transaction
- Search Transactions
- Filter by:
  - Type
  - Category
  - Date
- Recurring Transaction Support
- Payment Method Tracking

---

## Category Management
- Default Categories
- Custom Categories
- Add/Edit/Delete Categories
- Safe Delete Protection

---

## Budget Tracking
- Monthly Overall Budget
- Category-wise Budgets
- Budget vs Actual Tracking
- Near-limit Alerts
- Budget Exceeded Warnings

---

## Analytics
- Income vs Expense Line Chart
- Category-wise Expense Pie Chart
- Spending Pattern Analysis
- Financial Insights

---

## CSV Import System
- Upload Bank Statement CSV
- CSV Parsing
- Auto Transaction Detection
- Auto Category Detection
- Duplicate Detection
- Import Preview Before Confirmation
- Import Summary

---

## Financial Intelligence
- Financial Health Score (0–100)
- Smart Insight Engine
- Expense Prediction (Moving Average)
- Subscription Detection
- Smart Alerts

---

## Profile + Settings
- Update Profile
- Change Password
- Currency Preference
- Dark Mode Preference
- Notification Settings

---

# Folder Structure

```text
spendwise/
│
├── index.php
├── login.php
├── signup.php
├── logout.php
│
├── config/
├── includes/
├── modules/
├── assets/
├── api/
├── classes/
├── database/
├── logs/
└── README.md
