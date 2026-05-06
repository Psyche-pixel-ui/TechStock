CREATE DATABASE IF NOT EXISTS `techstock_db`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `techstock_db`;

CREATE TABLE IF NOT EXISTS `Supplier` (
  `Supplier_ID`     INT          NOT NULL AUTO_INCREMENT COMMENT 'A unique number for each supplier',
  `Supplier_Name`   VARCHAR(150) NOT NULL                COMMENT 'The name of the supplier or their company',
  `Contact_Number`  VARCHAR(20)  NOT NULL                COMMENT 'The supplier phone number',
  `Email_Address`   VARCHAR(150)          DEFAULT NULL   COMMENT 'The supplier email address',
  `Address`         VARCHAR(255)          DEFAULT NULL   COMMENT 'The supplier full address',
  `Created_At`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Supplier_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Saves the contact details of all suppliers';


CREATE TABLE IF NOT EXISTS `Product` (
  `Product_ID`      INT            NOT NULL AUTO_INCREMENT COMMENT 'A unique number for each product',
  `Product_Name`    VARCHAR(200)   NOT NULL                COMMENT 'The name of the product (e.g., Intel Core i5)',
  `Category`        ENUM(
                      'CPU',
                      'GPU',
                      'RAM',
                      'Storage',
                      'Motherboard',
                      'Peripheral',
                      'Other'
                    )              NOT NULL                 COMMENT 'The type of product (e.g., CPU, GPU, RAM, SSD, Peripheral)',
  `Price`           DECIMAL(10,2)  NOT NULL DEFAULT 0.00   COMMENT 'How much the product costs per piece',
  `Stock_Quantity`  INT            NOT NULL DEFAULT 0      COMMENT 'How many pieces are currently in the shop',
  `Min_Stock_Level` INT            NOT NULL DEFAULT 1      COMMENT 'The lowest allowed stock before the system sends a low-stock alert',
  `Created_At`      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_At`      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Product_ID`),
  CONSTRAINT `chk_price`           CHECK (`Price` >= 0),
  CONSTRAINT `chk_stock_quantity`  CHECK (`Stock_Quantity` >= 0),
  CONSTRAINT `chk_min_stock_level` CHECK (`Min_Stock_Level` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Saves the details of every product sold at RavenTech';

CREATE TABLE IF NOT EXISTS `Stock_Transaction` (
  `Transaction_ID`   INT          NOT NULL AUTO_INCREMENT  COMMENT 'A unique number for each transaction',
  `Product_ID`       INT          NOT NULL                 COMMENT 'Which product was involved',
  `Supplier_ID`      INT                   DEFAULT NULL    COMMENT 'Which supplier sent the product (only for Stock In)',
  `Quantity`         INT          NOT NULL                 COMMENT 'How many pieces were added or removed',
  `Transaction_Date` DATETIME     NOT NULL                 COMMENT 'The date and time it happened',
  `Type`             ENUM(
                       'Stock In',
                       'Stock Out'
                     )            NOT NULL                 COMMENT 'Whether it was Stock In (arrived) or Stock Out (sold)',
  `Remarks`          VARCHAR(255)          DEFAULT NULL    COMMENT 'Any extra notes (optional)',
  `Created_At`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Transaction_ID`),
  CONSTRAINT `chk_quantity` CHECK (`Quantity` > 0),
  CONSTRAINT `fk_txn_product`
    FOREIGN KEY (`Product_ID`)
    REFERENCES `Product` (`Product_ID`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `fk_txn_supplier`
    FOREIGN KEY (`Supplier_ID`)
    REFERENCES `Supplier` (`Supplier_ID`)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Records every time a product is added or removed from stock';


INSERT INTO `Supplier` (`Supplier_Name`, `Contact_Number`, `Email_Address`, `Address`) VALUES
  ('PC Express',     '0917-111-2222', 'info@pcx.com',         'Davao City'),
  ('Dynaquest PC',   '0918-333-4444', 'sales@dynaquest.com',  'Davao City'),
  ('EasyPC Trading', '0919-555-6666', 'orders@easypc.com.ph', 'Davao City');

INSERT INTO `Product` (`Product_Name`, `Category`, `Price`, `Stock_Quantity`, `Min_Stock_Level`) VALUES
  ('Intel Core i5-12400F',    'CPU',         8500.00, 12, 3),
  ('AMD Ryzen 5 5600X',       'CPU',         9200.00,  2, 3),
  ('MSI B550 Tomahawk',       'Motherboard', 7800.00,  5, 2),
  ('Corsair 16GB DDR4',       'RAM',         2800.00,  0, 4),
  ('Samsung 970 EVO 1TB',     'Storage',     4500.00,  8, 3),
  ('Logitech G102',            'Peripheral',   850.00, 15, 5),
  ('ASUS RTX 3060 12GB',      'GPU',        18500.00,  3, 2),
  ('Kingston A400 SSD 480GB', 'Storage',     1800.00,  1, 3);

INSERT INTO `Stock_Transaction` (`Product_ID`, `Supplier_ID`, `Quantity`, `Transaction_Date`, `Type`, `Remarks`) VALUES
  (1, 1,    5,  '2026-04-20 09:00:00', 'Stock In',  'Regular restock'),
  (4, 2,    10, '2026-04-21 10:30:00', 'Stock In',  NULL),
  (6, NULL, 3,  '2026-04-22 11:00:00', 'Stock Out', 'Sold'),
  (2, NULL, 2,  '2026-04-23 14:15:00', 'Stock Out', 'Sold'),
  (8, 3,    4,  '2026-04-24 09:45:00', 'Stock In',  NULL);

