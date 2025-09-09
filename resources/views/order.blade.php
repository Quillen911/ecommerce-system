<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Sipariş Adımları</title>
    <style>
        :root {
            --bg: #0A0A0A;
            --text: #FFFFFF;
            --muted: #CCCCCC;
            --line: #333333;
            --accent: #404040;
            --success: #00E6B8;
            --danger: #FF5555;
            --card: #1F1F1F;
            --primary: #00E6B8;
            --warn: #FFB84D;
        }
        * { box-sizing: border-box }
        html, body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
        }
        body {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
        }
        .shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 20px 80px;
        }
        
        .page-header {
            background: var(--card);
            border-bottom: 1px solid var(--line);
            padding: 20px 0;
            margin: -24px -20px 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: var(--text);
        }
        .header-subtitle {
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
        }
        
        .nav-toolbar {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .nav-section {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .btn {
            border: 1px solid var(--accent);
            background: var(--accent);
            color: var(--text);
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn:hover {
            background: var(--accent);
            border-color: var(--accent);
            transform: translateY(-1px);
        }
        .btn.outline {
            background: transparent;
            color: var(--accent);
            border-color: var(--accent);
        }
        .btn.outline:hover {
            background: var(--accent);
            color: var(--text);
        }
        .btn.success {
            background: var(--success);
            border-color: var(--success);
        }
        .btn.success:hover {
            background: var(--primary);
        }
        .btn.ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--line);
        }
        .btn.ghost:hover {
            background: var(--accent);
            color: var(--text);
        }
        
        .table-container {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }
        .table-wrap {
            overflow: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        thead th {
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            background: var(--accent);
            border-bottom: 1px solid var(--line);
            padding: 16px 20px;
            text-align: left;
        }
        tbody td {
            padding: 20px;
            border-bottom: 1px solid var(--line);
            font-size: 14px;
            color: var(--text);
        }
        tbody tr:hover {
            background: var(--accent);
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .product-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        
        .campaign {
            background: var(--card);
            border: 1px solid var(--success);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        .campaign-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--success);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .campaign-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }
        .campaign-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--line);
        }
        .campaign-item:last-child {
            border-bottom: none;
        }
        .campaign-label {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
        }
        .campaign-value {
            font-weight: 600;
            color: var(--text);
        }
        
        .summary {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .summary-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 16px;
            text-align: center;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--line);
            font-size: 14px;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 18px;
            color: var(--success);
        }
        
        .payment-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-top: 32px;
        }
        
        .payment-methods {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0 0 20px 0;
        }
        
        .credit-card-item {
            background: var(--accent);
            border: 1px solid var(--line);
            padding: 16px;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            align-items: center;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-bottom: 12px;
        }
        .credit-card-item:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .credit-card-item:last-child {
            margin-bottom: 0;
        }
        .credit-card-item.selected {
            border-color: var(--primary);
            background: rgba(0, 212, 170, 0.1);
        }
        .credit-card-item input[type="radio"] {
            margin: 0;
            accent-color: var(--primary);
        }
        .credit-card-item label {
            cursor: pointer;
            flex: 1;
            font-weight: 500;
        }
        .card-info {
            color: var(--muted);
            font-size: 12px;
            margin-top: 4px;
        }
        
        .new-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .new-card form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .new-card .full {
            grid-column: 1 / -1;
        }
        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .field label {
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 4px;
        }
        .field input, .field select {
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            outline: none;
            background: var(--accent);
            color: var(--text);
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .field input:focus, .field select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1);
        }
        .field input:hover, .field select:hover {
            border-color: var(--primary);
        }
        
        .notice {
            padding: 12px 16px;
            border: 1px solid var(--line);
            margin: 0 0 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .notice.success {
            color: var(--success);
            background: rgba(0, 212, 170, 0.1);
            border-color: var(--success);
        }
        .notice.error {
            color: var(--danger);
            background: rgba(255, 68, 68, 0.1);
            border-color: var(--danger);
        }
        
        .empty-state {
            text-align: center;
            padding: 64px 32px;
            background: var(--card);
            border-radius: 20px;
            border: 2px dashed var(--line);
            margin: 32px 0;
        }
        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }
        .empty-state p {
            color: var(--muted);
            margin-bottom: 0;
        }
        
        .checkout-section {
            margin-top: 32px;
            text-align: center;
        }
        .checkout-btn {
            background: var(--success);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        .checkout-btn:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        }
        
        .step-progress {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .step-list {
            display: flex;
            justify-content: space-between;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: var(--line);
            z-index: 1;
        }
        .step-item.completed:not(:last-child)::after {
            background: var(--success);
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            border: 2px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: var(--muted);
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }
        .step-item.active .step-circle {
            background: var(--success);
            border-color: var(--success);
            color: var(--text);
        }
        .step-item.completed .step-circle {
            background: var(--success);
            border-color: var(--success);
            color: var(--text);
        }
        .step-item.completed .step-circle::before {
            content: '✓';
            font-size: 16px;
        }
        .step-label {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            text-align: center;
        }
        .step-item.active .step-label {
            color: var(--success);
            font-weight: 600;
        }
        .step-item.completed .step-label {
            color: var(--text);
        }

        .step-content {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .step-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--success);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .address-item {
            background: var(--accent);
            border: 2px solid var(--success);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        .address-label {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
        }
        .address-details {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.4;
        }

        .shipping-option {
            background: var(--accent);
            border: 2px solid var(--success);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        .shipping-title {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
        }
        .shipping-details {
            font-size: 13px;
            color: var(--muted);
        }

        .payment-option {
            background: var(--accent);
            border: 2px solid var(--success);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        .payment-title {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
        }
        .payment-details {
            font-size: 13px;
            color: var(--muted);
        }

        .cvc-help-btn {
            background: var(--line);
            border: none;
            border-radius: 4px;
            padding: 8px 10px;
            color: var(--muted);
            cursor: help;
            font-size: 12px;
            font-weight: 600;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .cvc-help-btn:hover {
            background: var(--primary);
            color: var(--text);
        }
        .cvc-help-btn[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .cvc-help-btn[title]:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #333;
            z-index: 1000;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        .field.error input {
            border-color: #dc2626 !important;
        }
        .field.error .field-error {
            display: block;
        }

        .save-card-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 16px;
            background: rgba(0, 212, 170, 0.05);
            border-radius: 8px;
            border: 2px solid rgba(0, 212, 170, 0.2);
            transition: all 0.2s ease;
        }
        .save-card-label:hover {
            border-color: rgba(0, 212, 170, 0.4);
            background: rgba(0, 212, 170, 0.08);
        }
        #save_new_card {
            width: 20px;
            height: 20px;
            accent-color: var(--success);
            cursor: pointer;
        }
        .save-card-title {
            font-weight: 600;
            color: var(--success);
            margin-bottom: 4px;
        }
        .save-card-subtitle {
            font-size: 13px;
            color: var(--muted);
        }

        .new-card-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 16px;
            background: rgba(0, 212, 170, 0.05);
            border-radius: 8px;
            border: 2px solid rgba(0, 212, 170, 0.2);
            transition: all 0.2s ease;
        }
        .new-card-toggle:hover {
            border-color: rgba(0, 212, 170, 0.4);
            background: rgba(0, 212, 170, 0.08);
        }
        .new-card-toggle input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--success);
            cursor: pointer;
        }
        .toggle-text {
            font-weight: 600;
            color: var(--success);
        }

        .step-navigation {
            display: flex;
            justify-content: center;
            gap: 12px;
            align-items: center;
            margin-top: 24px;
        }
        .btn-back {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--line);
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            background: var(--accent);
            color: var(--text);
        }
        .btn-next {
            background: var(--success);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-next:hover {
            background: var(--primary);
            transform: translateY(-1px);
        }
        .btn-next:disabled {
            background: var(--line);
            color: var(--muted);
            cursor: not-allowed;
            transform: none;
        }
        .complete-order-btn {
            background: var(--success);
            color: white;
            font-size: 16px;
            font-weight: 600;
            padding: 16px 32px;
        }
        .complete-order-btn:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }

        .main-layout {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 32px !important;
            margin-top: 24px;
            width: 100% !important;
            max-width: none !important;
        }
        .left-column {
            grid-column: 1;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            height: fit-content;
        }
        .right-column {
            grid-column: 2;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .product-list {
            margin-bottom: 24px;
        }
        .product-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--line);
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: visible;
            flex-shrink: 0;
            border: 2px solid var(--line);
            position: relative;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }
        .product-quantity {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--success);
            color: black;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            z-index: 10;
            min-width: 24px;
            min-height: 24px;
            border: 2px solid var(--card);
        }
        .product-details {
            flex: 1;
        }
        .product-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
            color: var(--text);
        }
        .product-description {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 8px;
        }
        .product-prices {
            margin-top: 4px;
        }
        .discounted-price {
            font-weight: 600;
            color: var(--success);
            font-size: 14px;
        }

        .price-summary {
            background: var(--accent);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }
        .summary-row {
            border-bottom: 1px solid var(--line);
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .discount-row {
            color: var(--success);
        }
        .total-row {
            font-weight: 600;
            font-size: 16px;
            color: var(--success);
            margin-top: 8px;
            padding-top: 12px;
            border-top: none;
            border-bottom: none !important;
        }
        .tax-info {
            text-align: center;
            margin-top: 8px;
            font-size: 11px;
            color: var(--muted);
        }

        @media (min-width: 769px) {
            .main-layout {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 32px !important;
            }
            .left-column {
                grid-column: 1 !important;
            }
            .right-column {
                grid-column: 2 !important;
            }
        }

        @media (max-width: 768px) {
            .shell {
                padding: 16px 12px 60px;
            }
            .page-header {
                margin: -16px -12px 20px;
                padding: 20px 0;
            }
            .header-content {
                padding: 0 12px;
            }
            h1 {
                font-size: 20px;
            }
            .nav-toolbar {
                padding: 12px 16px;
                margin-bottom: 16px;
            }
            .nav-section {
                justify-content: center;
            }
            .campaign-info {
                grid-template-columns: 1fr;
            }
            .payment-section {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .new-card form {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .summary {
                padding: 20px;
                margin: 20px 0;
            }
            .table-container {
                margin-bottom: 20px;
            }
            thead th {
                padding: 12px 16px;
                font-size: 11px;
            }
            tbody td {
                padding: 16px;
                font-size: 13px;
            }

            .step-progress {
                padding: 16px;
                margin-bottom: 16px;
            }
            .step-list {
                flex-direction: column;
                gap: 16px;
            }
            .step-item:not(:last-child)::after {
                display: none;
            }
            .step-item {
                flex-direction: row;
                justify-content: flex-start;
                gap: 12px;
            }
            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
            .step-label {
                margin-top: 0;
                font-size: 14px;
            }

            .step-content {
                padding: 16px;
                margin-bottom: 16px;
            }
            .step-title {
                font-size: 16px;
                margin-bottom: 16px;
            }
            .step-number {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            .step-navigation {
                flex-direction: column;
                gap: 12px;
                margin-top: 16px;
            }
            .btn-back, .btn-next {
                width: 100%;
                justify-content: center;
                padding: 14px 20px;
            }

            .main-layout {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .left-column {
                grid-column: 1;
                order: 2;
            }
            .right-column {
                grid-column: 1;
                order: 1;
            }


            #credit-card-form {
                margin-top: 16px;
            }
            #credit-card-form input {
                padding: 12px;
                font-size: 14px;
            }
            .cvc-help-btn {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }

            .summary {
                padding: 16px;
                margin-bottom: 16px;
            }
            .summary-title {
                font-size: 16px;
                margin-bottom: 12px;
            }
            .summary-row {
                padding: 8px 0;
                font-size: 14px;
            }

            .campaign {
                padding: 16px;
                margin-bottom: 16px;
            }
            .campaign-title {
                font-size: 16px;
                margin-bottom: 12px;
            }
            .campaign-item {
                padding: 6px 0;
                font-size: 13px;
            }

            .product-image {
                width: 60px;
                height: 60px;
                overflow: visible;
            }
            .product-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 4px;
            }
        }
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div>
            <h1>Sipariş Adımları</h1>
            <div class="header-subtitle">Adres, kargo ve ödeme bilgilerinizi tamamlayın</div>
        </div>
    </div>
</div>

<div class="shell">
    <div class="nav-toolbar">
        <div class="nav-section">
            <a href="/bag" class="btn ghost">
                ← Sepete Dön
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="notice success">
            ✓ {{session('success')}}
        </div>
    @endif
    
    @if(session('error'))
        <div class="notice error">
            ✗ {{session('error')}}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="empty-state">
            <div style="font-size:48px;margin-bottom:16px;opacity:0.5;">📋</div>
            <h3>Siparişiniz Yok</h3>
            <p>Sepetinizde ürün bulunmuyor.</p>
            <a href="/bag" class="btn" style="margin-top:16px">
                ← Sepete Dön
            </a>
        </div>
    @else
    
        <!-- Step Progress -->
        <div class="step-progress">
            <ul class="step-list">
                <li class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Adres</div>
                </li>
                <li class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Kargo</div>
                </li>
                <li class="step-item" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Ödeme</div>
                </li>
            </ul>
        </div>

        <div class="main-layout">
            <!-- Sol Taraf - Adımlar -->
            <div class="left-column">
                <!-- Step 1: Address -->
                <div class="step-content" id="step-1">
                    <div class="step-title">
                        <div class="step-number">1</div>
                        Adres Seçimi
                    </div>

                    <!-- Mevcut Adres -->
                    <div class="address-item selected">
                        <div class="address-label">🏠 Teslimat Adresi</div>
                        <div class="address-details">
                            @foreach($addresses as $address)
                                @if($address->is_active)
                                <label for="shipping_address_{{ $address->id }}" style="display: block; margin-bottom: 12px; padding: 12px; border: 1px solid var(--line); border-radius: 8px; cursor: pointer;">
                                    <input type="radio" name="shipping_address_id" value="{{ $address->id }}" id="shipping_address_{{ $address->id }}" style="margin-right: 8px;">
                                    <div>
                                        <div style="font-weight:600;margin-bottom:4px;">{{ $address->title }}</div>
                                        <div class="address-info">{{ $address->first_name }} {{ $address->last_name }}</div>
                                        <div class="address-info">{{ $address->phone }}</div>
                                        <div class="address-info">{{ $address->address_line_1 }}</div>
                                        <div class="address-info">{{ $address->address_line_2 }}</div>
                                        <div class="address-info">{{ $address->district }} {{ $address->city }} {{ $address->postal_code }}</div>
                                        <div class="address-info">{{ $address->country }}</div>
                                        <div class="address-info">{{ $address->notes }}</div>
                                    </div>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="step-navigation">
                        <div></div>
                        <button type="button" class="btn-next" onclick="nextStep(2)">
                            Kargo ile Devam Et →
                        </button>
                    </div>

                </div>

                <!-- Step 2: Shipping -->
                <div class="step-content" id="step-2" style="display: none;">
                    <div class="step-title">
                        <div class="step-number">2</div>
                        Kargo Seçimi
                    </div>
                    
                    <div class="shipping-option selected">
                        <div class="shipping-title">🚚 Ücretsiz Kargo</div>
                        <div class="shipping-details">1-3 iş günü içerisinde kargoya verilir / Ücretsiz</div>
                    </div>
                    
                    <div class="step-navigation">
                        <button type="button" class="btn-back" onclick="prevStep(1)">
                            ← Adrese Geri Dön
                        </button>
                        <button type="button" class="btn-next" onclick="nextStep(3)">
                            Ödeme ile Devam Et →
                        </button>
                    </div>
                </div>

                <!-- Step 3: Payment -->
                <div class="step-content" id="step-3" style="display: none;">
                    <form id="order-form" action="{{ route('done') }}" method="POST">
                        @csrf
                        <input type="hidden" name="credit_card_id" id="credit_card_id" value="">
                        <input type="hidden" name="shipping_address_id" id="shipping_address_id" value="">
                        <input type="hidden" name="billing_address_id" id="billing_address_id" value="">
                    <div class="step-title">
                        <div class="step-number">3</div>
                        Ödeme Yöntemi
                    </div>

                    {{-- Kayıtlı kart VARSA --}}
                    @if($creditCards->count() > 0)
                        <div id="saved-cards-section">
                            <div class="payment-option selected">
                                <div class="payment-title">
                                    <strong>Kayıtlı Kredi Kartlarım</strong>
                                </div>
                                @foreach($creditCards as $card)
                                    <div class="credit-card-item">
                                        <input type="radio" name="credit_card_selection" value="{{ $card->id }}" id="card_{{ $card->id }}" autocomplete="off">
                                        <label for="card_{{ $card->id }}">
                                            <div>
                                                <div style="font-weight:600;margin-bottom:4px;">{{ $card->name }}</div>
                                                <div class="card-info">**** **** **** {{ $card->last_four_digits }}</div>
                                                <div class="card-info">{{ $card->card_holder_name }}</div>
                                                <div class="card-info">{{ $card->expire_month }}/{{ $card->expire_year }}</div>
                                                @if($card->iyzico_card_token)
                                                    <div class="card-info" style="color:var(--success);font-size:12px;">✓ Güvenli kart</div>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div style="margin-bottom: 16px;">
                                <label class="new-card-toggle">
                                    <input type="checkbox" name="credit_card_selection" value="new_card" id="new_card" autocomplete="off">
                                    <span class="checkmark"></span>
                                    <span class="toggle-text">Yeni Kart ile Devam Et</span>
                                </label>
                            </div>
                            
                            <!-- Yeni Kart Formu (Gizli) -->
                            <div id="new-card-form" style="display: none; margin-top: 20px;">
                                <div class="payment-option selected">
                                    <div class="payment-title">💳 Yeni Kredi Kartı</div>
                                    <div class="payment-details">Güvenli ödeme ile</div>
                                </div>
                                
                                <div id="credit-card-form" style="margin-top: 20px;">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                        <div class="field">
                                            <label>Kart Numarası</label>
                                            <input type="text" name="new_card_number" id="new_card_number" placeholder="Kart Numarası" maxlength="19">
                                            <div class="field-error" id="new_card_number_error"></div>
                                        </div>
                                        <div class="field">
                                            <label>Kart Üzerindeki İsim (Kart Sahibi)</label>
                                            <input type="text" name="new_card_holder_name" id="new_card_holder_name" placeholder="AHMET YILMAZ">
                                            <div class="field-error" id="new_card_holder_name_error"></div>
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 16px; margin-bottom: 16px;">
                                        <div class="field">
                                            <label>Ay / Yıl</label>
                                            <input type="text" name="new_expire_date" id="new_expire_date" placeholder="MM/YY" data-month-field="new_expire_month" data-year-field="new_expire_year">
                                            <input type="hidden" name="new_expire_month" id="new_expire_month">
                                            <input type="hidden" name="new_expire_year" id="new_expire_year">
                                            <div class="field-error" id="new_expire_date_error"></div>
                                        </div>
                                        <div class="field">
                                            <label>CVC</label>
                                            <input type="password" name="new_cvv" id="new_cvv" placeholder="123" maxlength="3" autocomplete="off">
                                            <div class="field-error" id="new_cvv_error"></div>
                                        </div>
                                        <div style="display: flex; align-items: end; position: relative;">
                                            <button type="button" class="cvc-help-btn" title="Kartınızın arkasındaki 3 haneli güvenlik kodu">?</button>
                                        </div>
                                        <div class="field full">
                                            <label>Kartınız için isim</label>
                                            <input type="text" name="new_card_name" id="new_card_name" placeholder="Yeni Kart">
                                            <div class="field-error" id="new_card_name_error"></div>
                                        </div>
                                        <div class="field full" style="margin-top:20px;">
                                            <label class="save-card-label">
                                                <input type="checkbox" id="save_new_card_toggle" name="save_new_card" value="1" checked autocomplete="off">
                                                <div class="save-card-text">
                                                    <div class="save-card-title">Bu kartı kayıtlı kartlarıma ekle</div>
                                                    <div class="save-card-subtitle">Sonraki ödemelerinizde tek tıkla ödeme yapabilirsiniz</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Kayıtlı kart YOKSA: sadece Yeni Kart formu --}}
                        <div id="new-card-form">
                            <div class="payment-option selected">
                                <div class="payment-title">💳 Kredi Kartı</div>
                                <div class="payment-details">Güvenli ödeme ile</div>
                            </div>
                            
                            <div id="credit-card-form" style="margin-top: 20px;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                    <div class="field">
                                        <label>Kart Numarası</label>
                                        <input type="text" name="new_card_number" id="new_card_number" placeholder="Kart Numarası" maxlength="19">
                                        <div class="field-error" id="new_card_number_error"></div>
                                    </div>
                                    <div class="field">
                                        <label>Kart Üzerindeki İsim (Kart Sahibi)</label>
                                        <input type="text" name="new_card_holder_name" id="new_card_holder_name" placeholder="AHMET YILMAZ">
                                        <div class="field-error" id="new_card_holder_name_error"></div>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 16px; margin-bottom: 16px;">
                                    <div class="field">
                                        <label>Ay / Yıl</label>
                                        <input type="text" name="new_expire_date" id="new_expire_date" placeholder="MM/YY" data-month-field="new_expire_month" data-year-field="new_expire_year">
                                        <input type="hidden" name="new_expire_month" id="new_expire_month">
                                        <input type="hidden" name="new_expire_year" id="new_expire_year">
                                        <div class="field-error" id="new_expire_date_error"></div>
                                    </div>
                                    <div class="field">
                                        <label>CVC</label>
                                        <input type="password" name="new_cvv" id="new_cvv" placeholder="123" maxlength="3" autocomplete="off">
                                        <div class="field-error" id="new_cvv_error"></div>
                                    </div>
                                    <div style="display: flex; align-items: end; position: relative;">
                                        <button type="button" class="cvc-help-btn" title="Kartınızın arkasındaki 3 haneli güvenlik kodu">?</button>
                                    </div>
                                    <div class="field full">
                                        <label>Kartınız için isim</label>
                                        <input type="text" name="new_card_name" id="new_card_name" placeholder="Yeni Kart">
                                        <div class="field-error" id="new_card_name_error"></div>
                                    </div>
                                    <div class="field full" style="margin-top:20px;">
                                        <label class="save-card-label">
                                            <input type="checkbox" id="save_new_card" name="save_new_card" value="1" checked autocomplete="off">
                                            <div class="save-card-text">
                                                <div class="save-card-title">Bu kartı kayıtlı kartlarıma ekle</div>
                                                <div class="save-card-subtitle">Sonraki ödemelerinizde tek tıkla ödeme yapabilirsiniz</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const hid = document.getElementById('credit_card_id');
                                if (hid) hid.value = 'new_card';
                            });
                        </script>
                    @endif

                    <!-- Fatura Adresi Bölümü (Her iki durumda da görünür) -->
                    <div style="margin-top: 16px;">
                        <label class="new-card-toggle">
                            <input type="checkbox" id="billing_same_as_shipping" checked autocomplete="off">
                            <span class="checkmark"></span>
                            <span class="toggle-text">Fatura Adresim Teslimat Adresim ile aynı</span>
                        </label>
                    </div>

                    <!-- Fatura adresi seçimi (gizli) -->
                    <div class="address-item" id="billing-address-item" style="display: none; margin-top: 20px;">
                        <div class="address-label">Fatura Adresi</div>
                        <div class="address-details">
                            @foreach($addresses as $address)
                                @if($address->is_active)
                                <label for="billing_address_{{ $address->id }}" style="display: block; margin-bottom: 12px; padding: 12px; border: 1px solid var(--line); border-radius: 8px; cursor: pointer;">
                                    <input type="radio" name="billing_address_selection" value="{{ $address->id }}" id="billing_address_{{ $address->id }}" style="margin-right: 8px;">
                                    <div>
                                        <div style="font-weight:600;margin-bottom:4px;">{{ $address->title }}</div>
                                        <div class="address-info">{{ $address->first_name }} {{ $address->last_name }}</div>
                                        <div class="address-info">{{ $address->phone }}</div>
                                        <div class="address-info">{{ $address->address_line_1 }}</div>
                                        <div class="address-info">{{ $address->address_line_2 }}</div>
                                        <div class="address-info">{{ $address->district }} {{ $address->city }} {{ $address->postal_code }}</div>
                                        <div class="address-info">{{ $address->country }}</div>
                                        <div class="address-info">{{ $address->notes }}</div>
                                    </div>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div id="new-billing-address-toggle" style="margin-bottom: 16px; margin-top: 20px;">
                        <label class="new-card-toggle">
                            <input type="checkbox" id="new_billing_address" name="new_billing_address" value="1" autocomplete="off">
                            <span class="checkmark"></span>
                            <span class="toggle-text">Yeni Fatura Adresi</span>
                        </label>
                    </div>
                    <div id="new-billing-address-form" style="display: none; margin-top: 20px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div class="field">
                                <label>Başlık</label>
                                <input type="text" name="new_billing_address_title" id="new_billing_address_title" placeholder="Başlık" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_title_error"></div>
                            </div>
                            <div class="field">
                                <label>Ad</label>
                                <input type="text" name="new_billing_address_first_name" id="new_billing_address_first_name" placeholder="Ad" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_first_name_error"></div>
                            </div>
                            <div class="field">
                                <label>Soyad</label>
                                <input type="text" name="new_billing_address_last_name" id="new_billing_address_last_name" placeholder="Soyad" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_last_name_error"></div>
                            </div>
                            <div class="field">
                                <label>Telefon</label>
                                <input type="text" name="new_billing_address_phone" id="new_billing_address_phone" placeholder="Telefon" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_phone_error"></div>
                            </div>
                            <div class="field">
                                <label>Adres</label>
                                <input type="text" name="new_billing_address_address" id="new_billing_address_address" placeholder="Adres" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_address_error"></div>
                            </div>
                            <div class="field">
                                <label>Adres 2</label>
                                <input type="text" name="new_billing_address_address_2" id="new_billing_address_address_2" placeholder="Adres 2" maxlength="255">
                                <div class="field-error" id="new_billing_address_address_2_error"></div>
                            </div>
                            <div class="field">
                                <label>İlçe</label>
                                <input type="text" name="new_billing_address_district" id="new_billing_address_district" placeholder="İlçe" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_district_error"></div>
                            </div>
                            <div class="field">
                                <label>İl</label>
                                <input type="text" name="new_billing_address_city" id="new_billing_address_city" placeholder="İl" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_city_error"></div>
                            </div>
                            <div class="field">
                                <label>Posta Kodu</label>
                                <input type="text" name="new_billing_address_postal_code" id="new_billing_address_postal_code" placeholder="Posta Kodu" maxlength="10" required>
                                <div class="field-error" id="new_billing_address_postal_code_error"></div>
                            </div>
                            <div class="field">
                                <label>Ülke</label>
                                <input type="text" name="new_billing_address_country" id="new_billing_address_country" placeholder="Ülke" maxlength="255" required>
                                <div class="field-error" id="new_billing_address_country_error"></div>
                            </div>
                            <div class="field">
                                <label>Notlar</label>
                                <textarea name="new_billing_address_notes" id="new_billing_address_notes" placeholder="Notlar" rows="3"></textarea>
                                <div class="field-error" id="new_billing_address_notes_error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Siparişi Tamamla Butonu -->
                    <div class="step-navigation" style="margin-top: 24px;">
                        <button type="button" class="btn-back" onclick="prevStep(2)">
                            ← Kargoya Geri Dön
                        </button>
                        <button type="submit" class="btn-next complete-order-btn">
                            ✓ Siparişi Tamamla
                        </button>
                    </div>
                    
                    <div style="text-align: center; margin-top: 12px; font-size: 12px; color: var(--muted);">
                        Ödemeler güvenli ve şifrelidir.
                    </div>
                    </form>
                </div>
            </div>
            
            <!-- Sağ Taraf - Ürünler, Fiyat Özeti ve Kampanya -->
            <div class="right-column">
                <!-- Ürün Listesi -->
                <div class="product-list">
                    @foreach($products as $p)
                        <div class="product-item">
                            <div class="product-image">
                                <img src="{{ $p->product?->first_image ?? '/images/no-image.png' }}" alt="{{ $p->product?->title ?? 'Ürün' }}">
                                <div class="product-quantity">{{ $p->quantity }}</div>
                            </div>
                            <div class="product-details">
                                <div class="product-name">{{ $p->product?->title ?? 'Ürün bilgisi yok' }}</div>
                                <div class="product-description">{{ $p->product?->author ?? 'Yazar bilgisi yok' }}</div>
                                <div class="product-prices">
                                    @if(($p->product?->list_price ?? 0) > 0)
                                        <span class="discounted-price">₺{{ number_format($p->product?->list_price ?? 0, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                    
                <!-- Fiyat Özeti -->
                <div class="price-summary">
                    <div class="summary-row">
                        <span>Ara Toplam</span>
                        <span>₺{{ number_format($total,2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Teslimat / Kargo</span>
                        <span>{{ $cargoPrice == 0 ? "Ücretsiz" : "₺".number_format($cargoPrice,2) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="summary-row discount-row">
                            <span>İndirim</span>
                            <span>-₺{{ number_format($discount,2) }}</span>
                        </div>
                    @endif
                    <div class="summary-row total-row">
                        <span>Toplam</span>
                        <span>₺{{ number_format(floor($finalPrice * 100) / 100,2) }}</span>
                    </div>
                    <div class="tax-info">
                        Vergi ₺{{ number_format(floor($finalPrice * 100) / 100 * 0.01,2) }}
                    </div>
                    </div>
                    
                @if(isset($bestCampaign['discount']) && $bestCampaign['discount'])
                    <div class="campaign">
                        <div class="campaign-title">
                            ⭐ Aktif Kampanya
                        </div>
                        <div class="campaign-info">
                            <div class="campaign-item">
                                <span class="campaign-label">Kampanya</span>
                                <span class="campaign-value">{{ $bestCampaign['description'] }}</span>
                            </div>
                            <div class="campaign-item">
                                <span class="campaign-label">İndirim</span>
                                <span class="campaign-value">{{ number_format($bestCampaign['discount'],2) }} TL</span>
                            </div>
                            @if(isset($bestCampaign['store_name']) && $bestCampaign['store_name'])
                                <div class="campaign-item">
                                    <span class="campaign-label">Mağaza</span>
                                    <span class="campaign-value">{{ $bestCampaign['store_name'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    window.creditCardsData = @json($creditCards->keyBy('id'));
</script>
<script src="{{ asset('js/order.js') }}"></script>
</body>
</html>
