# TechStock — PC Parts Inventory System

## Description

TechStock is a simple system for managing PC parts. It lets users handle products and suppliers, track stock levels, and record stock in and out.

---

## Setup (XAMPP)

1. Start Apache and MySQL in XAMPP
2. Copy the project to:
   C:\xampp\htdocs\TechStock\
3. Open http://localhost/phpmyadmin - SQL tab - run:
   backend/database.sql
4. Open backend/config/db.php and set:
   DB_HOST = localhost
   DB_USER = root
   DB_PASS = (leave blank)
   DB_NAME = techstock_db
5. Run the system:
   http://localhost/TechStock/

---

## Features (So Far)

* Manage products and suppliers
* Add, edit, and delete records
* Stock In / Stock Out
* Auto update of stock quantity
* View basic reports (low stock, summary)

---

## Notes

* Use localhost to run the system
* Make sure Apache and MySQL are running
