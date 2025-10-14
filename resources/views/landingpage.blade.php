<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iRequest - Document Request System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dark-red: #8B0000;
            --gold: #FFD700;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --dark-gray: #333333;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-gray);
            color: var(--dark-gray);
            overflow-x: hidden;
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: var(--dark-red);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }
        
        .navbar.scrolled {
            padding: 0.5rem 5%;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .logo-text {
            color: var(--gold);
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
        }
        
        .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 0.5rem 0;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-links a:hover {
            color: var(--gold);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--gold);
            transition: var(--transition);
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .hamburger {
            display: none;
            cursor: pointer;
            color: var(--white);
            font-size: 1.5rem;
        }
        
        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/heros.png');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 0 5%;
            margin-top: 70px;
        }
        
        .hero-content {
            max-width: 600px;
            color: var(--white);
            animation: fadeInUp 1s ease;
        }
        
        .hero-title {
            font-size: 3rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .hero-title span {
            color: var(--gold);
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .cta-button {
            background-color: var(--gold);
            color: var(--dark-red);
            border: none;
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            width: fit-content;
        }
        
        .cta-button:hover {
            background-color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Steps Section */
        .steps-section {
            padding: 5rem 5%;
            background-color: var(--white);
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: var(--dark-red);
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--gold);
        }
        
        .steps-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
        }
        
        .step-card {
            flex: 1;
            min-width: 250px;
            background-color: var(--light-gray);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .step-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: var(--dark-red);
        }
        
        .step-icon {
            font-size: 3rem;
            color: var(--dark-red);
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }
        
        .step-card:hover .step-icon {
            transform: scale(1.1);
        }
        
        .step-number {
            background-color: var(--gold);
            color: var(--dark-red);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .step-title {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--dark-red);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .step-description {
            color: var(--dark-gray);
            line-height: 1.6;
        }
        
        /* Promo Section */
        .promo-section {
            padding: 5rem 5%;
            background-color: var(--light-gray);
        }
        
        .promo-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }
        
        .promo-card {
            flex: 1;
            min-width: 300px;
            max-width: 400px;
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            position: relative;
        }
        
        .promo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .promo-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .promo-card:hover .promo-img {
            transform: scale(1.05);
        }
        
        .promo-content {
            padding: 1.5rem;
            position: relative;
        }
        
        .promo-icon {
            position: absolute;
            top: -30px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: var(--dark-red);
            color: var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        
        .promo-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--dark-red);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .promo-text {
            color: var(--dark-gray);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .promo-button {
            background-color: var(--dark-red);
            color: var(--white);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .promo-button:hover {
            background-color: var(--gold);
            color: var(--dark-red);
        }
        
        /* FAQ Section */
        .faq-section {
            padding: 5rem 5%;
            background-color: var(--white);
        }
        
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .faq-item {
            margin-bottom: 1rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .faq-question {
            background-color: var(--light-gray);
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
            color: var(--dark-red);
            transition: var(--transition);
        }
        
        .faq-question:hover {
            background-color: #e9e9e9;
        }
        
        .faq-question i {
            transition: var(--transition);
        }
        
        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: var(--white);
        }
        
        .faq-item.active .faq-question {
            background-color: var(--dark-red);
            color: var(--white);
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        
        .faq-item.active .faq-answer {
            padding: 1.5rem;
            max-height: 500px;
        }
        
        /* Footer */
        .footer {
            background-color: var(--dark-red);
            color: var(--white);
            padding: 4rem 5% 2rem;
        }
        
        .footer-container {
            display: flex;
            flex-wrap: wrap;
            gap: 3rem;
            justify-content: space-between;
        }
        
        .footer-logo {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .footer-logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .footer-about {
            line-height: 1.6;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        
        .footer-social {
            display: flex;
            gap: 1rem;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .social-icon:hover {
            background-color: var(--gold);
            color: var(--dark-red);
            transform: translateY(-3px);
        }
        
        .footer-links {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-title {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            color: var(--gold);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-list {
            list-style: none;
        }
        
        .footer-list li {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-list a {
            color: var(--white);
            text-decoration: none;
            transition: var(--transition);
            opacity: 0.9;
        }
        
        .footer-list a:hover {
            color: var(--gold);
            padding-left: 5px;
        }
        
        .footer-contact {
            flex: 1;
            min-width: 250px;
        }
        
        .contact-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: flex-start;
        }
        
        .contact-icon {
            color: var(--gold);
            margin-top: 3px;
        }
        
        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1500;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background-color: var(--white);
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(20px);
            transition: all 0.3s ease;
        }
        
        .modal.active .modal-content {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--dark-red);
            color: var(--white);
            border-radius: 10px 10px 0 0;
        }
        
        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .close-button {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--white);
            transition: var(--transition);
        }
        
        .close-button:hover {
            color: var(--gold);
            transform: rotate(90deg);
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-input.error {
            border-color: #dc3545;
        }
        
        .form-input:focus {
            border-color: var(--dark-red);
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.1);
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.5rem;
            color: var(--dark-red);
        }
        
        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        
        .modal-button {
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .primary-button {
            background-color: var(--dark-red);
            color: var(--white);
            border: none;
        }
        
        .primary-button:hover {
            background-color: #6d0000;
        }
        
        .secondary-button {
            background-color: transparent;
            border: 1px solid #ddd;
            color: var(--dark-gray);
        }
        
        .secondary-button:hover {
            background-color: #f5f5f5;
        }
        
        /* Track Document Modal */
        .track-input-group {
            display: flex;
            gap: 0;
        }
        
        .track-input {
            flex: 1;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            padding-left: 3rem;
        }
        
        .track-button {
            background-color: var(--gold);
            color: var(--dark-red);
            border: none;
            padding: 0 1.5rem;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .track-button:hover {
            background-color: #e6c200;
        }
        
        /* Terms Modal */
        .terms-content {
            max-height: 300px;
            overflow-y: auto;
            padding: 1rem;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            border: 1px solid #eee;
        }
        
        .terms-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--dark-red);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .terms-text {
            line-height: 1.6;
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        
        .terms-text::before {
            content: '•';
            color: var(--dark-red);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 0.5rem;
        }
        
        .checkbox-input {
            margin-right: 0.7rem;
        }
        
        /* Request Form Modal */
        .form-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .form-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #eee;
            z-index: 1;
        }
        
        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 30%;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: bold;
            transition: var(--transition);
        }
        
        .step.active .step-number {
            background-color: var(--gold);
            color: var(--dark-red);
        }
        
        .step.completed .step-number {
            background-color: var(--dark-red);
            color: var(--white);
        }
        
        .step-label {
            font-size: 0.9rem;
            color: #999;
            transition: var(--transition);
        }
        
        .step.active .step-label {
            color: var(--dark-red);
            font-weight: 500;
        }
        
        .step.completed .step-label {
            color: var(--dark-gray);
        }
        
        /* Selection Screen Styles */
        .selection-screen {
            text-align: center;
            padding: 2rem 0;
        }
        
        .selection-title h3 {
            color: var(--dark-red);
            font-size: 1.8rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .selection-title p {
            color: var(--dark-gray);
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .selection-options {
            display: flex;
            gap: 2rem;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .selection-option {
            background: var(--white);
            border: 2px solid var(--gray);
            border-radius: 12px;
            padding: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 300px;
            height: 200px;
            text-align: left;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .selection-option:hover {
            border-color: var(--dark-red);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(139, 0, 0, 0.15);
        }
        
        .option-icon {
            width: 60px;
            height: 60px;
            background: var(--dark-red);
            color: var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .option-content h4 {
            color: var(--dark-red);
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        
        .option-content p {
            color: var(--dark-gray);
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        /* Form Container */
        .form-container {
            width: 100%;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray);
        }
        
        .form-header h3 {
            color: var(--dark-red);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .form-header p {
            color: var(--dark-gray);
            font-size: 1rem;
        }
        
        /* FORM STEP FIX */
        .form-step {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .form-step.active {
            display: block;
            opacity: 1;
            animation: fadeIn 0.5s ease;
        }
        
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .back-button {
            background-color: transparent;
            border: 1px solid #ddd;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .next-button {
            background-color: var(--dark-red);
            color: var(--white);
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .submit-button {
            background-color: var(--gold);
            color: var(--dark-red);
            border: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Confirmation Modal Styles */
        .summary-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .summary-section h5 {
            color: var(--dark-red);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .summary-section p {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }
        
        .summary-section strong {
            color: var(--dark-gray);
            min-width: 150px;
            display: inline-block;
        }
        
        /* Track Result Modal */
        .track-result {
            text-align: center;
            padding: 2rem;
        }
        
        .track-status {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .status-icon {
            font-size: 2rem;
        }
        
        .status-pending {
            color: #ffc107;
        }
        
        .status-processing {
            color: #0d6efd;
        }
        
        .status-ready {
            color: #198754;
        }
        
        .status-delivered {
            color: #0dcaf0;
        }
        
        .status-cancelled {
            color: #dc3545;
        }
        
        .track-details {
            text-align: left;
            margin-top: 2rem;
        }
        
        .detail-item {
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        
        .detail-label {
            font-weight: 500;
            color: var(--dark-red);
            min-width: 120px;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background-color: var(--dark-red);
                flex-direction: column;
                align-items: center;
                padding: 2rem 0;
                gap: 2rem;
                transition: var(--transition);
                z-index: 999;
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .hamburger {
                display: block;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .steps-container, .promo-cards {
                flex-direction: column;
            }
            
            .step-card, .promo-card {
                min-width: 100%;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
        
        .doc-type-group {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #eee;
            padding: 18px 18px 8px 18px;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .doc-type-checkbox {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f2f2f2;
        }
        
        .doc-type-checkbox:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }
        
        .doc-type-checkbox.on-cooldown {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .doc-type-checkbox.on-cooldown label {
            color: #6c757d !important;
            font-style: italic;
        }
        
        .doc-type-checkbox.on-cooldown .quantity-selector {
            opacity: 0.5;
            pointer-events: none;
        }
        
        .doc-type-checkbox input[type="checkbox"] {
            accent-color: var(--dark-red);
            width: 18px;
            height: 18px;
            margin-right: 12px;
        }
        
        .doc-type-checkbox label {
            flex: 1;
            margin-left: 8px;
            font-size: 1.08rem;
            color: #333;
            font-weight: 500;
            cursor: pointer;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            background: #f7f7f7;
            border-radius: 6px;
            padding: 2px 8px;
            gap: 4px;
            min-width: 100px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        
        .qty-btn {
            background: var(--dark-red);
            color: #fff;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .qty-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .doc-qty {
            width: 38px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            height: 28px;
            background: #fff;
            margin: 0 2px;
            font-size: 1rem;
        }
        
        /* Floating Chatbot */
        .floating-chatbot-bottom {
            position: fixed;
            right: 30px;
            bottom: 30px;
            z-index: 9999;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            border-radius: 50px;
            padding: 10px 22px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: box-shadow 0.2s;
        }
        
        .floating-chatbot-bottom:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.22);
        }
        
        .chatbot-icon-label i {
            font-size: 22px;
            color: #8B0000;
            margin-right: 10px;
        }
        
        .chatbot-icon-label span {
            font-weight: 600;
            color: #333;
            font-size: 16px;
        }
        
        /* Chatbot Modal Styles */
        #chatbotModal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.3);
            justify-content: center;
            align-items: center;
        }
        
        .chatbot-modal {
            max-width: 500px;
            width: 90vw;
            height: 80vh;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .chatbot-modal .modal-header {
            background: var(--dark-red);
            color: var(--white);
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .chatbot-modal .modal-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .chatbot-modal .modal-body {
            flex: 1;
            padding: 0;
            overflow: hidden;
        }
        
        /* Chat Container */
        .chat-container {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
        }
        
        /* Chat Suggestions Section */
        .chat-suggestions {
            background: var(--white);
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
        }
        
        /* Message Styles */
        .message {
            margin-bottom: 15px;
            animation: fadeInUp 0.3s ease;
        }
        
        .message-content {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }
        
        .bot-message .message-content {
            justify-content: flex-start;
        }
        
        .user-message .message-content {
            justify-content: flex-end;
            flex-direction: row-reverse;
        }
        
        .bot-avatar {
            width: 32px;
            height: 32px;
            background: var(--dark-red);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 14px;
            flex-shrink: 0;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
        }
        
        .bot-message .message-bubble {
            background: var(--white);
            color: var(--dark-gray);
            border: 1px solid #e9ecef;
            border-bottom-left-radius: 4px;
        }
        
        .user-message .message-bubble {
            background: var(--dark-red);
            color: var(--white);
            border-bottom-right-radius: 4px;
        }
        
        .message-bubble p {
            margin: 0;
            line-height: 1.4;
            font-size: 14px;
        }
        
        /* Typing Indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 12px 16px;
            background: var(--white);
            border: 1px solid #e9ecef;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            max-width: 70px;
            margin-bottom: 15px;
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            background: #6c757d;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }
        
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typing {
            0%, 80%, 100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        /* FAQ Question Bubbles */
        .faq-questions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .faq-question-bubble {
            background: var(--white);
            border: 1px solid #e9ecef;
            border-radius: 18px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 85%;
            align-self: flex-start;
        }
        
        .faq-question-bubble:hover {
            background: #f8f9fa;
            border-color: var(--dark-red);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(139, 0, 0, 0.1);
        }
        
        .faq-question-bubble i {
            color: var(--dark-red);
            font-size: 14px;
            flex-shrink: 0;
        }
        
        .faq-question-bubble span {
            font-size: 14px;
            color: var(--dark-gray);
            line-height: 1.3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo-container">
            <img src="images/logo.png" alt="iRequest Logo" class="logo-img">
            <span class="logo-text">iRequest</span>
        </div>
        
        <div class="nav-links">
            <a href="#home"><i class="fas fa-home"></i> Home</a>
            <a href="#" id="trackDocumentBtn"><i class="fas fa-search"></i> Track Document</a>
        </div>
        
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title">IBACMI <span>ODRMS</span></h1>
            <p class="hero-subtitle">Our streamlined process makes requesting transcripts, diplomas, and other academic records simple and efficient.</p>
            <button class="cta-button" id="requestDocumentBtn">
                <i class="fas fa-file-import"></i> Request a Document
            </button>
        </div>
    </section>
    
    <!-- Steps Section -->
    <section class="steps-section">
        <h2 class="section-title">How It Works</h2>
        
        <div class="steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon"><i class="fas fa-file-alt"></i></div>
                <h3 class="step-title">
                    <i class="fas fa-pen-fancy"></i> Submit Request
                </h3>
                <p class="step-description">Fill out our simple online form with your personal and document details.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                <h3 class="step-title">
                    <i class="fas fa-clipboard-check"></i> Review & Payment
                </h3>
                <p class="step-description">Verify your information and complete the secure payment process.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon"><i class="fas fa-truck"></i></div>
                <h3 class="step-title">
                    <i class="fas fa-envelope-open-text"></i> Receive Documents
                </h3>
                <p class="step-description">Get notified when your documents are ready for pickup or delivery.</p>
            </div>
        </div>
    </section>
    
    <!-- Promo Section -->
    <section class="promo-section">
        <h2 class="section-title">Why Choose iRequest</h2>
        
        <div class="promo-cards">
            <div class="promo-card">
                <div class="promo-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Fast Processing" class="promo-img">
                <div class="promo-content">
                    <h3 class="promo-title">
                        <i class="fas fa-clock"></i> Fast Processing
                    </h3>
                    <p class="promo-text">Our automated system ensures your requests are processed quickly, often within 24-48 hours.</p>
                    <button class="promo-button">
                        <i class="fas fa-arrow-right"></i> Learn More
                    </button>
                </div>
            </div>
            
            <div class="promo-card">
                <div class="promo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Secure Tracking" class="promo-img">
                <div class="promo-content">
                    <h3 class="promo-title">
                        <i class="fas fa-lock"></i> Secure Tracking
                    </h3>
                    <p class="promo-text">Monitor your document's status in real-time with our secure tracking system.</p>
                    <button class="promo-button">
                        <i class="fas fa-arrow-right"></i> Learn More
                    </button>
                </div>
            </div>
            
            <div class="promo-card">
                <div class="promo-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80" alt="24/7 Support" class="promo-img">
                <div class="promo-content">
                    <h3 class="promo-title">
                        <i class="fas fa-phone-alt"></i> 24/7 Support
                    </h3>
                    <p class="promo-text">Our dedicated support team is available around the clock to assist you.</p>
                    <button class="promo-button">
                        <i class="fas fa-arrow-right"></i> Learn More
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Floating Chatbot Icon -->
    <div id="floatingChatbot" class="floating-chatbot-bottom">
        <div class="chatbot-icon-label">
            <i class="fas fa-robot"></i>
            <span>Chat Assistant</span>
        </div>
    </div>
    
    <!-- Chatbot Modal -->
    <div class="modal" id="chatbotModal">
        <div class="modal-content chatbot-modal">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-robot"></i> iRequest Assistant</h3>
                <button class="close-button" id="closeChatbotModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="chat-container">
                    <div class="chat-messages" id="chatMessages">
                        <!-- Bot introduction message -->
                        <div class="message bot-message">
                            <div class="message-content">
                                <div class="bot-avatar">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="message-bubble">
                                    <p>👋 Hi! I'm your iRequest Assistant. How can I help you today?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ question bubbles at bottom -->
                    <div class="chat-suggestions">
                        <div class="faq-questions" id="faqQuestions">
                            <div class="faq-question-bubble" data-question="How long does it take to process my document request?">
                                <i class="fas fa-clock"></i>
                                <span>How long does it take to process my document request?</span>
                            </div>
                            <div class="faq-question-bubble" data-question="What payment methods do you accept?">
                                <i class="fas fa-credit-card"></i>
                                <span>What payment methods do you accept?</span>
                            </div>
                            <div class="faq-question-bubble" data-question="Can I request multiple documents at once?">
                                <i class="fas fa-files-o"></i>
                                <span>Can I request multiple documents at once?</span>
                            </div>
                            <div class="faq-question-bubble" data-question="How do I track my document request?">
                                <i class="fas fa-search"></i>
                                <span>How do I track my document request?</span>
                            </div>
                            <div class="faq-question-bubble" data-question="What if I need to cancel my request?">
                                <i class="fas fa-times-circle"></i>
                                <span>What if I need to cancel my request?</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Track Document Modal -->
    <div class="modal" id="trackDocumentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-search"></i> Track Your Document</h3>
                <button class="close-button" id="closeTrackModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="trackDocumentForm" action="{{ route('track.document') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="referenceNumber" class="form-label">
                            <i class="fas fa-hashtag"></i> Enter Reference Number
                        </label>
                        <div class="track-input-group">
                            <i class="fas fa-search input-icon"></i>
                            <input type="text" name="reference_number" id="referenceNumber" 
                                   class="form-input track-input" placeholder="e.g. REQ-2023-12345" required>
                            <button type="submit" class="track-button">
                                <i class="fas fa-search"></i> Track
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="modal-button secondary-button" id="cancelTrackModal">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
    
    <!-- Terms and Conditions Modal -->
    <div class="modal" id="termsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-file-contract"></i> Terms and Conditions</h3>
                <button class="close-button" id="closeTermsModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="terms-content">
                    <h4 class="terms-title"><i class="fas fa-gavel"></i> Document Request Agreement</h4>
                    <p class="terms-text">
                        By proceeding with your document request, you agree to the following terms and conditions:
                    </p>
                    <p class="terms-text">
                        1. All information provided must be accurate and complete. Falsification of information may result in cancellation of your request.
                    </p>
                    <p class="terms-text">
                        2. Document processing times may vary depending on the type of document requested and current workload.
                    </p>
                    <p class="terms-text">
                        3. Fees for document requests are non-refundable once processing has begun.
                    </p>
                    <p class="terms-text">
                        4. You are responsible for ensuring the delivery address is correct. Additional fees may apply for address corrections.
                    </p>
                    <p class="terms-text">
                        5. The institution reserves the right to refuse requests that violate policies or regulations.
                    </p>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="agreeTerms" class="checkbox-input">
                    <label for="agreeTerms">I have read and agree to the terms and conditions</label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button secondary-button" id="cancelTermsModal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="modal-button primary-button" id="agreeTermsBtn" disabled>
                    <i class="fas fa-check"></i> I Agree
                </button>
            </div>
        </div>
    </div>
    
    <!-- Request Form Modal -->
    <div class="modal" id="requestFormModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-file-alt"></i> Document Request Form</h3>
                <button class="close-button" id="closeRequestModal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Selection Screen -->
                <div id="selectionScreen" class="selection-screen">
                    <div class="selection-title">
                        <h3><i class="fas fa-user-graduate"></i> Select Request Type</h3>
                        <p>Please choose how you would like to submit your document request:</p>
                    </div>
                    <div class="selection-options">
                        <button class="selection-option" id="studentOption">
                            <div class="option-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="option-content">
                                <h4>Request as Student</h4>
                                <p>I am currently enrolled or recently graduated from this institution</p>
                            </div>
                        </button>
                        <button class="selection-option" id="alumniOption">
                            <div class="option-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="option-content">
                                <h4>Request as Alumni</h4>
                                <p>I am a graduate of this institution</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Student Form -->
                <div id="studentForm" class="form-container" style="display: none;">
                    <div class="form-steps">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Personal Info</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Contact Info</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Document Info</div>
                        </div>
                    </div>
                    
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" data-step="1">
                        <div class="form-group">
                            <label for="studentId" class="form-label">
                                <i class="fas fa-id-card"></i> Student ID
                            </label>
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="studentId" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="course" class="form-label">
                                <i class="fas fa-graduation-cap"></i> Course
                            </label>
                            <i class="fas fa-book input-icon"></i>
                            <select id="course" class="form-input" required>
                                <option value="">Select Course</option>
                                <option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY">BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</option>
                                <option value="BACHELOR OF SCIENCE IN ENTREPRENEURSHIP">BACHELOR OF SCIENCE IN ENTREPRENEURSHIP</option>
                                <option value="BACHELOR OF SCIENCE IN CRIMINOLOGY">BACHELOR OF SCIENCE IN CRIMINOLOGY</option>
                                <option value="BACHELOR OF ELEMENTARY EDUCATION">BACHELOR OF ELEMENTARY EDUCATION</option>
                                <option value="BACHELOR OF EARLY CHILDHOOD EDUCATION">BACHELOR OF EARLY CHILDHOOD EDUCATION</option>
                                <option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT">BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT</option>
                                <option value="BACHELOR OF PUBLIC ADMINISTRATION">BACHELOR OF PUBLIC ADMINISTRATION</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="firstName" class="form-label">
                                <i class="fas fa-user"></i> First Name
                            </label>
                            <i class="fas fa-signature input-icon"></i>
                            <input type="text" id="firstName" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="middleName" class="form-label">
                                <i class="fas fa-user"></i> Middle Name
                            </label>
                            <i class="fas fa-signature input-icon"></i>
                            <input type="text" id="middleName" class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="lastName" class="form-label">
                                <i class="fas fa-user"></i> Last Name
                            </label>
                            <i class="fas fa-signature input-icon"></i>
                            <input type="text" id="lastName" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="yearLevel" class="form-label">
                                <i class="fas fa-calendar-alt"></i> Year Level
                            </label>
                            <i class="fas fa-calendar input-icon"></i>
                            <select id="yearLevel" class="form-input" required>
                                <option value="">Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                            <div id="schoolYearsGroup" style="margin-top:10px;">
                                <label for="schoolYears" class="form-label"><i class="fas fa-calendar"></i> School Years Enrolled</label>
                                <select id="schoolYears" class="form-input" multiple size="4" required>
                                    <option value="2021-2022">2021-2022</option>
                                    <option value="2022-2023">2022-2023</option>
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2025-2026">2025-2026</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Contact Information -->
                    <div class="form-step" data-step="2">
                        <div class="form-group">
                            <label for="province" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Province
                            </label>
                            <i class="fas fa-map input-icon"></i>
                            <select id="province" class="form-input" required>
                                <option value="">Select Province</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city" class="form-label">
                                <i class="fas fa-city"></i> City
                            </label>
                            <i class="fas fa-building input-icon"></i>
                            <select id="city" class="form-input" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="barangay" class="form-label">
                                <i class="fas fa-map-pin"></i> Barangay
                            </label>
                            <i class="fas fa-map-marked-alt input-icon"></i>
                            <select id="barangay" class="form-input" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mobile" class="form-label">
                                <i class="fas fa-mobile-alt"></i> Mobile Number
                            </label>
                            <i class="fas fa-phone input-icon"></i>
                            <input type="tel" id="mobile" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <i class="fas fa-at input-icon"></i>
                            <input type="email" id="email" class="form-input" required>
                        </div>
                    </div>
                    
                    <!-- Step 3: Document Information -->
                    <div class="form-step" data-step="3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-file"></i> Select Document Type(s)
                            </label>
                            <div class="cooldown-info" style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.9rem;">
                                <i class="fas fa-info-circle" style="color: #2196f3; margin-right: 8px;"></i>
                                <strong>Cooldown Policy:</strong> You can only request the same document type once every 40 days. Document types on cooldown will be disabled. You can still request different document types even if one is on cooldown.
                            </div>
                            <div class="verification-info" style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.9rem;">
                                <i class="fas fa-envelope" style="color: #ffc107; margin-right: 8px;"></i>
                                <strong>Email Verification Required:</strong> After submitting your request, you'll receive a verification email. Click the link in the email to complete your request. Verification links expire in 24 hours.
                            </div>
                            <div class="approval-info" style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.9rem;">
                                <i class="fas fa-clock" style="color: #2196f3; margin-right: 8px;"></i>
                                <strong>Registrar Approval Required:</strong> After email verification, your request will be reviewed by the Registrar. You'll receive an email notification once it's approved or rejected. Reference numbers are only generated after approval.
                            </div>
                            <div id="cooldownSummary" style="display: none; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                                <h5 style="color: #856404; margin: 0 0 8px 0;"><i class="fas fa-exclamation-triangle"></i> Current Cooldown Status</h5>
                                <div id="cooldownSummaryContent"></div>
                            </div>
                            <div class="doc-type-group" id="documentTypesGroup">
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-tor" name="document_types" value="TRANSCRIPT OF RECORDS">
                                    <label for="doc-tor">TRANSCRIPT OF RECORDS</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="TRANSCRIPT OF RECORDS" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-tor-eval" name="document_types" value="TRANSCRIPT OF RECORDS FOR EVALUATION">
                                    <label for="doc-tor-eval">TRANSCRIPT OF RECORDS FOR EVALUATION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="TRANSCRIPT OF RECORDS FOR EVALUATION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-form137a" name="document_types" value="FORM 137A">
                                    <label for="doc-form137a">FORM 137A</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="FORM 137A" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-form138" name="document_types" value="FORM 138">
                                    <label for="doc-form138">FORM 138</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="FORM 138" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-hd" name="document_types" value="HONORABLE DISMISSAL">
                                    <label for="doc-hd">HONORABLE DISMISSAL</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="HONORABLE DISMISSAL" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-diploma" name="document_types" value="DIPLOMA">
                                    <label for="doc-diploma">DIPLOMA</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="DIPLOMA" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-cno" name="document_types" value="CERTIFICATE OF NO OBJECTION">
                                    <label for="doc-cno">CERTIFICATE OF NO OBJECTION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF NO OBJECTION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-cem" name="document_types" value="CERTIFICATE OF ENGLISH AS MEDIUM">
                                    <label for="doc-cem">CERTIFICATE OF ENGLISH AS MEDIUM</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF ENGLISH AS MEDIUM" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-cgm" name="document_types" value="CERTIFICATE OF GOOD MORAL">
                                    <label for="doc-cgm">CERTIFICATE OF GOOD MORAL</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF GOOD MORAL" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-cr" name="document_types" value="CERTIFICATE OF REGISTRATION">
                                    <label for="doc-cr">CERTIFICATE OF REGISTRATION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF REGISTRATION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-cc" name="document_types" value="CERTIFICATE OF COMPLETION">
                                    <label for="doc-cc">CERTIFICATE OF COMPLETION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF COMPLETION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-cg" name="document_types" value="CERTIFICATE OF GRADES">
                                    <label for="doc-cg">CERTIFICATE OF GRADES</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF GRADES" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-soa" name="document_types" value="STATEMENT OF ACCOUNT">
                                    <label for="doc-soa">STATEMENT OF ACCOUNT</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="STATEMENT OF ACCOUNT" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-sr" name="document_types" value="SERVICE RECORD">
                                    <label for="doc-sr">SERVICE RECORD</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="SERVICE RECORD" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-employment" name="document_types" value="EMPLOYMENT">
                                    <label for="doc-employment">EMPLOYMENT</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="EMPLOYMENT" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-pr" name="document_types" value="PERFORMANCE RATING">
                                    <label for="doc-pr">PERFORMANCE RATING</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="PERFORMANCE RATING" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-gwa" name="document_types" value="GWA CERTIFICATE">
                                    <label for="doc-gwa">GWA CERTIFICATE</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="GWA CERTIFICATE" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="doc-cav" name="document_types" value="CAV ENDORSEMENT">
                                    <label for="doc-cav">CAV ENDORSEMENT</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CAV ENDORSEMENT" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="purpose" class="form-label">
                                <i class="fas fa-bullseye"></i> Purpose of Request
                            </label>
                            <i class="fas fa-crosshairs input-icon"></i>
                            <select id="purpose" class="form-input" required>
                                <option value="">Select Purpose</option>
                                <option value="Further Studies">Further Studies</option>
                                <option value="Employment">Employment</option>
                                <option value="Scholarship">Scholarship</option>
                                <option value="Personal Record">Personal Record</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="specialInstructions" class="form-label">
                                <i class="fas fa-comment-alt"></i> Special Instructions (Optional)
                            </label>
                            <i class="fas fa-edit input-icon"></i>
                            <textarea id="specialInstructions" class="form-input" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-navigation">
                        <button class="modal-button secondary-button" id="studentBackToSelectionBtn" style="display: none;">
                            <i class="fas fa-arrow-left"></i> Back to Selection
                        </button>
                        <button class="modal-button back-button" id="backButton" disabled>
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button class="modal-button next-button" id="nextButton">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                        <button class="modal-button submit-button" id="submitButton" style="display: none;">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                    </div>
                </div>

                <!-- Alumni Form -->
                <div id="alumniForm" class="form-container" style="display: none;">
                    <div class="form-steps">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Personal Info</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Contact Info</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Document Info</div>
                        </div>
                    </div>
                    
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" data-step="1">
                        <div class="form-group">
                            <label for="alumniFirstName" class="form-label">
                                <i class="fas fa-user"></i> First Name
                            </label>
                            <i class="fas fa-signature input-icon"></i>
                            <input type="text" id="alumniFirstName" name="first_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniMiddleName" class="form-label">
                                <i class="fas fa-user"></i> Middle Name
                            </label>
                            <i class="fas fa-signature input-icon"></i>
                            <input type="text" id="alumniMiddleName" name="middle_name" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniLastName" class="form-label">
                                <i class="fas fa-user"></i> Last Name
                            </label>
                            <i class="fas fa-signature input-icon"></i>
                            <input type="text" id="alumniLastName" name="last_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniCourse" class="form-label">
                                <i class="fas fa-graduation-cap"></i> Course
                            </label>
                            <i class="fas fa-book input-icon"></i>
                            <select id="alumniCourse" name="course" class="form-input" required>
                                <option value="">Select Course</option>
                                <option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY">BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</option>
                                <option value="BACHELOR OF SCIENCE IN ENTREPRENEURSHIP">BACHELOR OF SCIENCE IN ENTREPRENEURSHIP</option>
                                <option value="BACHELOR OF SCIENCE IN CRIMINOLOGY">BACHELOR OF SCIENCE IN CRIMINOLOGY</option>
                                <option value="BACHELOR OF ELEMENTARY EDUCATION">BACHELOR OF ELEMENTARY EDUCATION</option>
                                <option value="BACHELOR OF EARLY CHILDHOOD EDUCATION">BACHELOR OF EARLY CHILDHOOD EDUCATION</option>
                                <option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT">BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT</option>
                                <option value="BACHELOR OF PUBLIC ADMINISTRATION">BACHELOR OF PUBLIC ADMINISTRATION</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniGraduationYear" class="form-label">
                                <i class="fas fa-calendar"></i> School Year Graduated
                            </label>
                            <i class="fas fa-calendar input-icon"></i>
                            <select id="alumniGraduationYear" name="graduation_year" class="form-input" required>
                                <option value="">Select School Year</option>
                                <option value="2022-2023">2022-2023</option>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2024-2025">2024-2025</option>
                                <option value="2025-2026">2025-2026</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniId" class="form-label">
                                <i class="fas fa-id-badge"></i> Alumni ID (Optional)
                            </label>
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="text" id="alumniId" name="alumni_id" class="form-input">
                        </div>
                    </div>
                    
                    <!-- Step 2: Contact Information -->
                    <div class="form-step" data-step="2">
                        <div class="form-group">
                            <label for="alumniProvince" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Province
                            </label>
                            <i class="fas fa-map input-icon"></i>
                            <select id="alumniProvince" name="province" class="form-input" required>
                                <option value="">Select Province</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniCity" class="form-label">
                                <i class="fas fa-city"></i> City/Municipality
                            </label>
                            <i class="fas fa-building input-icon"></i>
                            <select id="alumniCity" name="city" class="form-input" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniBarangay" class="form-label">
                                <i class="fas fa-map-pin"></i> Barangay
                            </label>
                            <i class="fas fa-map-marked-alt input-icon"></i>
                            <select id="alumniBarangay" name="barangay" class="form-input" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniEmail" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <i class="fas fa-at input-icon"></i>
                            <input type="email" id="alumniEmail" name="email" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniMobile" class="form-label">
                                <i class="fas fa-mobile-alt"></i> Mobile Number
                            </label>
                            <i class="fas fa-phone input-icon"></i>
                            <input type="tel" id="alumniMobile" name="mobile" class="form-input" required>
                        </div>
                    </div>
                    
                    <!-- Step 3: Document Information -->
                    <div class="form-step" data-step="3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-file"></i> Select Document Type(s)
                            </label>
                            <div class="cooldown-info" style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.9rem;">
                                <i class="fas fa-info-circle" style="color: #2196f3; margin-right: 8px;"></i>
                                <strong>Cooldown Policy:</strong> You can only request the same document type once every 40 days. Document types on cooldown will be disabled. You can still request different document types even if one is on cooldown.
                            </div>
                            <div class="verification-info" style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.9rem;">
                                <i class="fas fa-envelope" style="color: #ffc107; margin-right: 8px;"></i>
                                <strong>Email Verification Required:</strong> After submitting your request, you'll receive a verification email. Click the link in the email to complete your request. Verification links expire in 24 hours.
                            </div>
                            <div class="approval-info" style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.9rem;">
                                <i class="fas fa-clock" style="color: #2196f3; margin-right: 8px;"></i>
                                <strong>Registrar Approval Required:</strong> After email verification, your request will be reviewed by the Registrar. You'll receive an email notification once it's approved or rejected. Reference numbers are only generated after approval.
                            </div>
                            <div id="alumniCooldownSummary" style="display: none; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                                <h5 style="color: #856404; margin: 0 0 8px 0;"><i class="fas fa-exclamation-triangle"></i> Current Cooldown Status</h5>
                                <div id="alumniCooldownSummaryContent"></div>
                            </div>
                            <div class="doc-type-group" id="alumniDocumentTypesGroup">
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-tor" name="alumni_document_types" value="TRANSCRIPT OF RECORDS">
                                    <label for="alumni-doc-tor">TRANSCRIPT OF RECORDS</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="TRANSCRIPT OF RECORDS" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-tor-eval" name="alumni_document_types" value="TRANSCRIPT OF RECORDS FOR EVALUATION">
                                    <label for="alumni-doc-tor-eval">TRANSCRIPT OF RECORDS FOR EVALUATION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="TRANSCRIPT OF RECORDS FOR EVALUATION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-form137a" name="alumni_document_types" value="FORM 137A">
                                    <label for="alumni-doc-form137a">FORM 137A</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="FORM 137A" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-form138" name="alumni_document_types" value="FORM 138">
                                    <label for="alumni-doc-form138">FORM 138</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="FORM 138" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-hd" name="alumni_document_types" value="HONORABLE DISMISSAL">
                                    <label for="alumni-doc-hd">HONORABLE DISMISSAL</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="HONORABLE DISMISSAL" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-diploma" name="alumni_document_types" value="DIPLOMA">
                                    <label for="alumni-doc-diploma">DIPLOMA</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="DIPLOMA" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-cno" name="alumni_document_types" value="CERTIFICATE OF NO OBJECTION">
                                    <label for="alumni-doc-cno">CERTIFICATE OF NO OBJECTION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF NO OBJECTION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-cem" name="alumni_document_types" value="CERTIFICATE OF ENGLISH AS MEDIUM">
                                    <label for="alumni-doc-cem">CERTIFICATE OF ENGLISH AS MEDIUM</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF ENGLISH AS MEDIUM" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-cgm" name="alumni_document_types" value="CERTIFICATE OF GOOD MORAL">
                                    <label for="alumni-doc-cgm">CERTIFICATE OF GOOD MORAL</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF GOOD MORAL" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-cr" name="alumni_document_types" value="CERTIFICATE OF REGISTRATION">
                                    <label for="alumni-doc-cr">CERTIFICATE OF REGISTRATION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF REGISTRATION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-cc" name="alumni_document_types" value="CERTIFICATE OF COMPLETION">
                                    <label for="alumni-doc-cc">CERTIFICATE OF COMPLETION</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF COMPLETION" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-cg" name="alumni_document_types" value="CERTIFICATE OF GRADES">
                                    <label for="alumni-doc-cg">CERTIFICATE OF GRADES</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CERTIFICATE OF GRADES" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-soa" name="alumni_document_types" value="STATEMENT OF ACCOUNT">
                                    <label for="alumni-doc-soa">STATEMENT OF ACCOUNT</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="STATEMENT OF ACCOUNT" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-sr" name="alumni_document_types" value="SERVICE RECORD">
                                    <label for="alumni-doc-sr">SERVICE RECORD</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="SERVICE RECORD" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-employment" name="alumni_document_types" value="EMPLOYMENT">
                                    <label for="alumni-doc-employment">EMPLOYMENT</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="EMPLOYMENT" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-pr" name="alumni_document_types" value="PERFORMANCE RATING">
                                    <label for="alumni-doc-pr">PERFORMANCE RATING</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="PERFORMANCE RATING" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-gwa" name="alumni_document_types" value="GWA CERTIFICATE">
                                    <label for="alumni-doc-gwa">GWA CERTIFICATE</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="GWA CERTIFICATE" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="doc-type-checkbox">
                                    <input type="checkbox" id="alumni-doc-cav" name="alumni_document_types" value="CAV ENDORSEMENT">
                                    <label for="alumni-doc-cav">CAV ENDORSEMENT</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" disabled><i class="fas fa-minus"></i></button>
                                        <input type="number" min="1" value="1" class="doc-qty" data-doc="CAV ENDORSEMENT" disabled>
                                        <button type="button" class="qty-btn plus" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniPurpose" class="form-label">
                                <i class="fas fa-bullseye"></i> Purpose of Request
                            </label>
                            <i class="fas fa-crosshairs input-icon"></i>
                            <select id="alumniPurpose" name="purpose" class="form-input" required>
                                <option value="">Select Purpose</option>
                                <option value="Further Studies">Further Studies</option>
                                <option value="Employment">Employment</option>
                                <option value="Scholarship">Scholarship</option>
                                <option value="Personal Record">Personal Record</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="alumniSpecialInstructions" class="form-label">
                                <i class="fas fa-comment-alt"></i> Special Instructions (Optional)
                            </label>
                            <i class="fas fa-edit input-icon"></i>
                            <textarea id="alumniSpecialInstructions" name="special_instructions" class="form-input" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-navigation">
                        <button class="modal-button secondary-button" id="backToSelectionBtn" style="display: none;">
                            <i class="fas fa-arrow-left"></i> Back to Selection
                        </button>
                        <button class="modal-button secondary-button" id="alumniBackButton" disabled>
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button class="modal-button next-button" id="alumniNextButton">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                        <button class="modal-button submit-button" id="alumniSubmitButton" style="display: none;">
                            <i class="fas fa-paper-plane"></i> Submit Alumni Request
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal" id="summaryModal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-list"></i> Review Your Request</h3>
                <button class="close-button" id="closeSummaryModal">&times;</button>
            </div>
            <div class="modal-body" id="summaryContent">
                <!-- Summary will be injected here -->
            </div>
            <div class="modal-footer">
                <button class="modal-button secondary-button" id="editRequestBtn">
                    <i class="fas fa-edit"></i> Edit Request
                </button>
                <button class="modal-button primary-button" id="finalSubmitBtn">
                    <i class="fas fa-paper-plane"></i> Submit Final Request
                </button>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <div class="footer-logo-text">
                    <img src="images/logo.png" alt="iRequest Logo" class="footer-logo-img">
                    iRequest
                </div>
                <p class="footer-about">
                    iRequest simplifies the process of requesting academic documents from educational institutions, saving you time and effort.
                </p>
                <div class="footer-social">
                    <div class="social-icon"><i class="fab fa-facebook-f"></i></div>
                    <div class="social-icon"><i class="fab fa-twitter"></i></div>
                    <div class="social-icon"><i class="fab fa-instagram"></i></div>
                    <div class="social-icon"><i class="fab fa-linkedin-in"></i></div>
                </div>
            </div>
            <div class="footer-links">
                <h3 class="footer-title">
                    <i class="fas fa-link"></i> Quick Links
                </h3>
                <ul class="footer-list">
                    <li><i class="fas fa-chevron-right"></i> <a href="#home">Home</a></li>
                    <li><i class="fas fa-chevron-right"></i> <a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3 class="footer-title">
                    <i class="fas fa-envelope"></i> Contact Us
                </h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt contact-icon"></i>
                    <span>TN Pepito Street, Poblacion, 8709 Valencia City, Bukidnon, Philippines</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone-alt contact-icon"></i>
                    <span>09533179109</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope contact-icon"></i>
                    <span>irequest.odrms@gmail.com</span>
                </div>
            </div>
        </div>
        <div class="copyright">
            <i class="far fa-copyright"></i> 2025 iRequest. All rights reserved.
        </div>
    </footer>
    
    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Mobile Menu Toggle
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');
        
        hamburger.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            hamburger.innerHTML = navLinks.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });
        
        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });
        
        // Modal Handling
        const trackDocumentBtn = document.getElementById('trackDocumentBtn');
        const trackDocumentModal = document.getElementById('trackDocumentModal');
        const closeTrackModal = document.getElementById('closeTrackModal');
        const cancelTrackModal = document.getElementById('cancelTrackModal');
        
        trackDocumentBtn.addEventListener('click', function() {
            trackDocumentModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
        
        closeTrackModal.addEventListener('click', function() {
            trackDocumentModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        cancelTrackModal.addEventListener('click', function() {
            trackDocumentModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        // Terms Modal Handling
        const requestDocumentBtn = document.getElementById('requestDocumentBtn');
        const termsModal = document.getElementById('termsModal');
        const closeTermsModal = document.getElementById('closeTermsModal');
        const cancelTermsModal = document.getElementById('cancelTermsModal');
        const agreeTermsBtn = document.getElementById('agreeTermsBtn');
        const agreeTermsCheckbox = document.getElementById('agreeTerms');
        
        requestDocumentBtn.addEventListener('click', function() {
            termsModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
        
        closeTermsModal.addEventListener('click', function() {
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        cancelTermsModal.addEventListener('click', function() {
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        agreeTermsCheckbox.addEventListener('change', function() {
            agreeTermsBtn.disabled = !this.checked;
        });
        
        // Request Form Modal Handling
        const requestFormModal = document.getElementById('requestFormModal');
        const closeRequestModal = document.getElementById('closeRequestModal');
        const selectionScreen = document.getElementById('selectionScreen');
        const studentForm = document.getElementById('studentForm');
        const alumniForm = document.getElementById('alumniForm');
        
        agreeTermsBtn.addEventListener('click', function() {
            termsModal.style.display = 'none';
            requestFormModal.style.display = 'flex';
            // Show selection screen by default
            selectionScreen.style.display = 'block';
            studentForm.style.display = 'none';
            alumniForm.style.display = 'none';
        });
        
        closeRequestModal.addEventListener('click', function() {
            requestFormModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            // Reset to selection screen
            selectionScreen.style.display = 'block';
            studentForm.style.display = 'none';
            alumniForm.style.display = 'none';
        });
        
        // Selection Screen Logic
        document.getElementById('studentOption').addEventListener('click', function() {
            selectionScreen.style.display = 'none';
            studentForm.style.display = 'block';
            alumniForm.style.display = 'none';
            // Show back to selection button on first step
            document.getElementById('studentBackToSelectionBtn').style.display = 'block';
            // Check available document types for student
            checkAvailableDocumentTypes(document.getElementById('studentId').value, false);
        });
        
        document.getElementById('alumniOption').addEventListener('click', function() {
            selectionScreen.style.display = 'none';
            studentForm.style.display = 'none';
            alumniForm.style.display = 'block';
            // Initialize alumni PSGC dropdowns
            initializeAlumniPSGC();
            // Reset alumni form to first step
            resetAlumniForm();
            // Check available document types for alumni when email is entered
            document.getElementById('alumniEmail').addEventListener('blur', function() {
                if (this.value) {
                    checkAvailableDocumentTypes(this.value, true);
                }
            });
        });
        
        // Back to Selection Button (Alumni)
        document.getElementById('backToSelectionBtn').addEventListener('click', function() {
            selectionScreen.style.display = 'block';
            studentForm.style.display = 'none';
            alumniForm.style.display = 'none';
        });

        // Back to Selection Button (Student)
        document.getElementById('studentBackToSelectionBtn').addEventListener('click', function() {
            selectionScreen.style.display = 'block';
            studentForm.style.display = 'none';
            alumniForm.style.display = 'none';
        });
        
        // Show/hide school years group when year level is selected
        document.getElementById('yearLevel').addEventListener('change', function() {
            var schoolYearsGroup = document.getElementById('schoolYearsGroup');
            if (this.value) {
                schoolYearsGroup.style.display = 'block';
            } else {
                schoolYearsGroup.style.display = 'none';
                // Clear selection
                Array.from(document.getElementById('schoolYears').options).forEach(opt => opt.selected = false);
            }
        });

        // Check available documents when student ID changes
        document.getElementById('studentId').addEventListener('blur', function() {
            if (this.value && studentForm.style.display === 'block') {
                checkAvailableDocumentTypes(this.value, false);
            }
        });

        // Hide school years group by default on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('schoolYearsGroup').style.display = 'none';
            
            // Set up daily cooldown refresh (every 24 hours)
            setInterval(function() {
                // Check if any forms are currently displayed
                if (studentForm.style.display === 'block') {
                    const studentId = document.getElementById('studentId').value;
                    if (studentId) {
                        checkAvailableDocumentTypes(studentId, false);
                    }
                } else if (alumniForm.style.display === 'block') {
                    const alumniEmail = document.getElementById('alumniEmail').value;
                    if (alumniEmail) {
                        checkAvailableDocumentTypes(alumniEmail, true);
                    }
                }
            }, 24 * 60 * 60 * 1000); // 24 hours in milliseconds
        });

        // Form Step Navigation
        const backButton = document.getElementById('backButton');
        const nextButton = document.getElementById('nextButton');
        const submitButton = document.getElementById('submitButton');
        const formSteps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('.step');
        
        let currentStep = 1;
        const totalSteps = 3;
        
        nextButton.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    // Hide current step
                    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('active');
                    document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
                    
                    // Show next step
                    currentStep++;
                    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');
                    document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
                    
                    // Mark previous step as completed
                    document.querySelector(`.step[data-step="${currentStep-1}"]`).classList.add('completed');
                    
                    // Update button visibility
                    document.getElementById('studentBackToSelectionBtn').style.display = 'none';
                    backButton.disabled = false;
                    if (currentStep === totalSteps) {
                        nextButton.style.display = 'none';
                        submitButton.style.display = 'block';
                    }
                }
            }
        });
        
        backButton.addEventListener('click', function() {
            // Hide current step
            document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            
            // Show previous step
            currentStep--;
            document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
            
            // Update button visibility
            nextButton.style.display = 'block';
            submitButton.style.display = 'none';
            if (currentStep === 1) {
                backButton.disabled = true;
                document.getElementById('studentBackToSelectionBtn').style.display = 'block';
            }
        });
        
        function validateStep(step) {
            // Simple validation - in a real app you'd want more robust validation
            let isValid = true;
            
            if (step === 1) {
                const requiredFields = ['studentId', 'course', 'firstName', 'lastName'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                });
            } else if (step === 2) {
                const requiredFields = ['province', 'city', 'barangay', 'mobile', 'email'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                    
                    // Additional email validation
                    if (field === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(input.value.trim())) {
                            isValid = false;
                            input.classList.add('error');
                        }
                    }
                });
            } else if (step === 3) {
                // Check if at least one document type is selected
                const selectedDocs = document.querySelectorAll('#documentTypesGroup input[type="checkbox"]:checked');
                if (selectedDocs.length === 0) {
                    isValid = false;
                    Swal.fire({
                        title: "No Document Types Selected",
                        text: "Please select at least one document type to request.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                }
                
                // Check if any selected documents are on cooldown
                const cooldownDocs = document.querySelectorAll('#documentTypesGroup input[type="checkbox"]:checked.on-cooldown');
                if (cooldownDocs.length > 0) {
                    isValid = false;
                    Swal.fire({
                        title: "Document Types on Cooldown",
                        text: "Some selected document types are currently on cooldown. Please deselect them or choose different document types.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                }
            }
            
            return isValid;
        }
        
        // Form Submission with Confirmation Modal
        submitButton.addEventListener('click', function() {
            if (!validateStep(currentStep)) return;

            // Gather all form data
            const docTypes = [];
            document.querySelectorAll('#documentTypesGroup input[type="checkbox"]:checked').forEach(checkbox => {
                const qty = checkbox.parentElement.querySelector('.doc-qty').value;
                docTypes.push({ type: checkbox.value, quantity: qty });
            });


            // School years enrolled (multi-select)
            const schoolYearsSelect = document.getElementById('schoolYears');
            const schoolYears = Array.from(schoolYearsSelect.selectedOptions).map(opt => opt.value);

            // Build summary HTML
            let docSummary = '';
            docTypes.forEach(doc => {
                docSummary += `<p><strong>${doc.type}:</strong> ${doc.quantity} copy/copies</p>`;
            });

            let schoolYearsHtml = '';
            if (schoolYears.length > 0) {
                schoolYearsHtml = `<p><strong>School Years Enrolled:</strong> ${schoolYears.join(', ')}</p>`;
            }

            const summaryHtml = `
                <div class="summary-section">
                    <h5><i class="fas fa-user"></i> Personal Information</h5>
                    <p><strong>Student ID:</strong> ${document.getElementById('studentId').value}</p>
                    <p><strong>Name:</strong> ${document.getElementById('firstName').value} ${document.getElementById('middleName').value} ${document.getElementById('lastName').value}</p>
                    <p><strong>Course:</strong> ${document.getElementById('course').value}</p>
                    <p><strong>Year Level:</strong> ${document.getElementById('yearLevel').value}</p>
                    ${schoolYearsHtml}
                </div>
                <div class="summary-section">
                    <h5><i class="fas fa-address-book"></i> Contact Information</h5>
                    <p><strong>Address:</strong> ${document.getElementById('barangay').value}, ${document.getElementById('city').value}, ${document.getElementById('province').value}</p>
                    <p><strong>Mobile:</strong> ${document.getElementById('mobile').value}</p>
                    <p><strong>Email:</strong> ${document.getElementById('email').value}</p>
                </div>
                <div class="summary-section">
                    <h5><i class="fas fa-file-alt"></i> Document Information</h5>
                    ${docSummary}
                    <p><strong>Purpose:</strong> ${document.getElementById('purpose').value}</p>
                    ${document.getElementById('specialInstructions').value ? `<p><strong>Special Instructions:</strong> ${document.getElementById('specialInstructions').value}</p>` : ''}
                </div>
            `;

            document.getElementById('summaryContent').innerHTML = summaryHtml;
            document.getElementById('summaryModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // Edit Request button
        document.getElementById('editRequestBtn').addEventListener('click', function() {
            document.getElementById('summaryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Final Submit button
        document.getElementById('finalSubmitBtn').addEventListener('click', async function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            // Gather all form data again
            const docTypes = [];
            document.querySelectorAll('#documentTypesGroup input[type="checkbox"]:checked').forEach(checkbox => {
                const qty = checkbox.parentElement.querySelector('.doc-qty').value;
                docTypes.push({ type: checkbox.value, quantity: qty });
            });

            try {
                const response = await fetch('/submit-request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        student_id: document.getElementById('studentId').value,
                        course: document.getElementById('course').value,
                        first_name: document.getElementById('firstName').value,
                        middle_name: document.getElementById('middleName').value,
                        last_name: document.getElementById('lastName').value,
                        year_level: document.getElementById('yearLevel').value,
                        school_years: Array.from(document.getElementById('schoolYears').selectedOptions).map(opt => opt.value),
                        province: document.getElementById('province').value,
                        city: document.getElementById('city').value,
                        barangay: document.getElementById('barangay').value,
                        mobile: document.getElementById('mobile').value,
                        email: document.getElementById('email').value,
                        document_types: docTypes,
                        purpose: document.getElementById('purpose').value,
                        special_instructions: document.getElementById('specialInstructions').value
                    })
                });

                let resultText = await response.text();
                let errorMessage = "Submission failed";
                let cooldownViolations = null;
                
                try {
                    // Try to parse JSON error message if available
                    const json = JSON.parse(resultText);
                    if (json && json.message) {
                        errorMessage = json.message;
                        if (json.cooldown_violations) {
                            cooldownViolations = json.cooldown_violations;
                        }
                    }
                } catch (e) {
                    // Not JSON, fallback to text
                    if (resultText) errorMessage = resultText;
                }

                document.getElementById('summaryModal').style.display = 'none';
                requestFormModal.style.display = 'none';
                document.body.style.overflow = 'auto';

                if (response.ok) {
                    const responseData = JSON.parse(resultText);
                    if (responseData.verification_sent) {
                        Swal.fire({
                            title: "Verification Email Sent!",
                            html: `
                                <div style="text-align: center;">
                                    <div style="font-size: 3rem; color: #28a745; margin-bottom: 20px;">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </div>
                                    <h3 style="color: #28a745; margin-bottom: 15px;">Check Your Email</h3>
                                    <p style="color: #333; line-height: 1.6; margin-bottom: 20px;">
                                        We've sent a verification link to <strong>${document.getElementById('email').value}</strong>
                                    </p>
                                    <div style="background: #e8f5e8; border: 1px solid #28a745; border-radius: 10px; padding: 15px; margin: 20px 0;">
                                        <p style="color: #155724; margin: 0; font-size: 0.9rem;">
                                            <i class="fas fa-info-circle"></i> 
                                            <strong>Important:</strong> Click the verification link in your email to complete your request. The link expires in 24 hours.
                                        </p>
                                    </div>
                                    <p style="color: #666; font-size: 0.9rem; margin-top: 15px;">
                                        Didn't receive the email? Check your spam folder or contact support.
                                    </p>
                                </div>
                            `,
                            icon: "success",
                            confirmButtonText: "I Understand",
                            width: '500px'
                        });
                    } else {
                        Swal.fire({
                            title: "Success!",
                            text: "Your request has been submitted successfully.",
                            icon: "success",
                            confirmButtonText: "OK"
                        });
                    }
                } else if (response.status === 429 && cooldownViolations) {
                    // Handle cooldown violations with detailed information
                    let cooldownHtml = '<div style="text-align: left; max-height: 300px; overflow-y: auto;">';
                    cooldownHtml += '<h4 style="color: #d63031; margin-bottom: 15px;">⚠️ Cooldown Period Violations</h4>';
                    
                    cooldownViolations.forEach(violation => {
                        cooldownHtml += `
                            <div style="background: #f8f9fa; padding: 12px; margin-bottom: 10px; border-radius: 8px; border-left: 4px solid #e74c3c;">
                                <h5 style="color: #e74c3c; margin: 0 0 8px 0;">${violation.document_type}</h5>
                                <p style="margin: 5px 0; color: #555;">
                                    <strong>Last Request:</strong> ${violation.last_request_date}<br>
                                    <strong>Remaining Days:</strong> ${violation.remaining_days} days<br>
                                    <strong>Message:</strong> ${violation.message}
                                </p>
                            </div>
                        `;
                    });
                    
                    cooldownHtml += '<p style="margin-top: 15px; color: #666; font-style: italic;">💡 Tip: You can still request different document types even if you are on cooldown for another type.</p>';
                    cooldownHtml += '</div>';
                    
                    Swal.fire({
                        title: "Cooldown Period Active",
                        html: cooldownHtml,
                        icon: "warning",
                        confirmButtonText: "I Understand",
                        width: '600px'
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: "Error!",
                    text: error.message,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });

        // Alumni Form Submission
        document.getElementById('alumniSubmitButton').addEventListener('click', async function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            // Gather alumni form data
            const docTypes = [];
            document.querySelectorAll('#alumniDocumentTypesGroup input[type="checkbox"]:checked').forEach(checkbox => {
                const qty = checkbox.parentElement.querySelector('.doc-qty').value;
                docTypes.push({ type: checkbox.value, quantity: qty });
            });

            try {
                const response = await fetch('/submit-alumni-request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        first_name: document.getElementById('alumniFirstName').value,
                        middle_name: document.getElementById('alumniMiddleName').value,
                        last_name: document.getElementById('alumniLastName').value,
                        course: document.getElementById('alumniCourse').value,
                        graduation_year: document.getElementById('alumniGraduationYear').value,
                        province: document.getElementById('alumniProvince').value,
                        city: document.getElementById('alumniCity').value,
                        barangay: document.getElementById('alumniBarangay').value,
                        email: document.getElementById('alumniEmail').value,
                        mobile: document.getElementById('alumniMobile').value,
                        alumni_id: document.getElementById('alumniId').value,
                        document_types: docTypes,
                        purpose: document.getElementById('alumniPurpose').value,
                        special_instructions: document.getElementById('alumniSpecialInstructions').value
                    })
                });

                let resultText = await response.text();
                let errorMessage = "Submission failed";
                let cooldownViolations = null;
                
                try {
                    const json = JSON.parse(resultText);
                    if (json && json.message) {
                        errorMessage = json.message;
                        if (json.cooldown_violations) {
                            cooldownViolations = json.cooldown_violations;
                        }
                    }
                } catch (e) {
                    if (resultText) errorMessage = resultText;
                }

                requestFormModal.style.display = 'none';
                document.body.style.overflow = 'auto';

                if (response.ok) {
                    const responseData = JSON.parse(resultText);
                    if (responseData.verification_sent) {
                        Swal.fire({
                            title: "Verification Email Sent!",
                            html: `
                                <div style="text-align: center;">
                                    <div style="font-size: 3rem; color: #28a745; margin-bottom: 20px;">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </div>
                                    <h3 style="color: #28a745; margin-bottom: 15px;">Check Your Email</h3>
                                    <p style="color: #333; line-height: 1.6; margin-bottom: 20px;">
                                        We've sent a verification link to <strong>${document.getElementById('alumniEmail').value}</strong>
                                    </p>
                                    <div style="background: #e8f5e8; border: 1px solid #28a745; border-radius: 10px; padding: 15px; margin: 20px 0;">
                                        <p style="color: #155724; margin: 0; font-size: 0.9rem;">
                                            <i class="fas fa-info-circle"></i> 
                                            <strong>Important:</strong> Click the verification link in your email to complete your request. The link expires in 24 hours.
                                        </p>
                                    </div>
                                    <p style="color: #666; font-size: 0.9rem; margin-top: 15px;">
                                        Didn't receive the email? Check your spam folder or contact support.
                                    </p>
                                </div>
                            `,
                            icon: "success",
                            confirmButtonText: "I Understand",
                            width: '500px'
                        });
                    } else {
                        Swal.fire({
                            title: "Success!",
                            text: "Your alumni request has been submitted successfully.",
                            icon: "success",
                            confirmButtonText: "OK"
                        });
                    }
                } else if (response.status === 429 && cooldownViolations) {
                    // Handle cooldown violations with detailed information
                    let cooldownHtml = '<div style="text-align: left; max-height: 300px; overflow-y: auto;">';
                    cooldownHtml += '<h4 style="color: #d63031; margin-bottom: 15px;">⚠️ Cooldown Period Violations</h4>';
                    
                    cooldownViolations.forEach(violation => {
                        cooldownHtml += `
                            <div style="background: #f8f9fa; padding: 12px; margin-bottom: 10px; border-radius: 8px; border-left: 4px solid #e74c3c;">
                                <h5 style="color: #e74c3c; margin: 0 0 8px 0;">${violation.document_type}</h5>
                                <p style="margin: 5px 0; color: #555;">
                                    <strong>Last Request:</strong> ${violation.last_request_date}<br>
                                    <strong>Remaining Days:</strong> ${violation.remaining_days} days<br>
                                    <strong>Message:</strong> ${violation.message}
                                </strong>
                            </div>
                        `;
                    });
                    
                    cooldownHtml += '<p style="margin-top: 15px; color: #666; font-style: italic;">💡 Tip: You can still request different document types even if you are on cooldown for another type.</p>';
                    cooldownHtml += '</div>';
                    
                    Swal.fire({
                        title: "Cooldown Period Active",
                        html: cooldownHtml,
                        icon: "warning",
                        confirmButtonText: "I Understand",
                        width: '600px'
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: "Error!",
                    text: error.message,
                    icon: "error",
                    confirmButtonText: "OK"
                });
    }
        });

        // Alumni Multi-Step Form Navigation
        const alumniBackButton = document.getElementById('alumniBackButton');
        const alumniNextButton = document.getElementById('alumniNextButton');
        const alumniSubmitButton = document.getElementById('alumniSubmitButton');
        const alumniFormSteps = document.querySelectorAll('#alumniForm .form-step');
        const alumniStepIndicators = document.querySelectorAll('#alumniForm .step');
        
        let alumniCurrentStep = 1;
        const alumniTotalSteps = 3;
        
        alumniNextButton.addEventListener('click', function() {
            if (validateAlumniStep(alumniCurrentStep)) {
                if (alumniCurrentStep < alumniTotalSteps) {
                    // Hide current step
                    document.querySelector(`#alumniForm .form-step[data-step="${alumniCurrentStep}"]`).classList.remove('active');
                    document.querySelector(`#alumniForm .step[data-step="${alumniCurrentStep}"]`).classList.remove('active');
                    
                    // Show next step
                    alumniCurrentStep++;
                    document.querySelector(`#alumniForm .form-step[data-step="${alumniCurrentStep}"]`).classList.add('active');
                    document.querySelector(`#alumniForm .step[data-step="${alumniCurrentStep}"]`).classList.add('active');
                    
                    // Mark previous step as completed
                    document.querySelector(`#alumniForm .step[data-step="${alumniCurrentStep-1}"]`).classList.add('completed');
                    
                    // Update button visibility
                    document.getElementById('backToSelectionBtn').style.display = 'none';
                    alumniBackButton.disabled = false;
                    if (alumniCurrentStep === alumniTotalSteps) {
                        alumniNextButton.style.display = 'none';
                        alumniSubmitButton.style.display = 'block';
                    }
                }
            }
        });
        
        alumniBackButton.addEventListener('click', function() {
            // Hide current step
            document.querySelector(`#alumniForm .form-step[data-step="${alumniCurrentStep}"]`).classList.remove('active');
            document.querySelector(`#alumniForm .step[data-step="${alumniCurrentStep}"]`).classList.remove('active');
            
            // Show previous step
            alumniCurrentStep--;
            document.querySelector(`#alumniForm .form-step[data-step="${alumniCurrentStep}"]`).classList.add('active');
            document.querySelector(`#alumniForm .step[data-step="${alumniCurrentStep}"]`).classList.add('active');
            
            // Update button visibility
            alumniNextButton.style.display = 'block';
            alumniSubmitButton.style.display = 'none';
            if (alumniCurrentStep === 1) {
                alumniBackButton.disabled = true;
                document.getElementById('backToSelectionBtn').style.display = 'block';
            }
        });
        
        function validateAlumniStep(step) {
            let isValid = true;
            
            if (step === 1) {
                const requiredFields = ['alumniFirstName', 'alumniLastName', 'alumniCourse', 'alumniGraduationYear'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                });
            } else if (step === 2) {
                const requiredFields = ['alumniProvince', 'alumniCity', 'alumniBarangay', 'alumniMobile', 'alumniEmail'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                    
                    // Additional email validation
                    if (field === 'alumniEmail') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(input.value.trim())) {
                            isValid = false;
                            input.classList.add('error');
                        }
                    }
                });
            } else if (step === 3) {
                // Check if at least one document type is selected
                const selectedDocs = document.querySelectorAll('#alumniDocumentTypesGroup input[type="checkbox"]:checked');
                if (selectedDocs.length === 0) {
                    isValid = false;
                    Swal.fire({
                        title: "No Document Types Selected",
                        text: "Please select at least one document type to request.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                }
                
                // Check if any selected documents are on cooldown
                const cooldownDocs = document.querySelectorAll('#alumniDocumentTypesGroup input[type="checkbox"]:checked.on-cooldown');
                if (cooldownDocs.length > 0) {
                    isValid = false;
                    Swal.fire({
                        title: "Document Types on Cooldown",
                        text: "Some selected document types are currently on cooldown. Please deselect them or choose different document types.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                }
            }
            
            return isValid;
        }

        function resetAlumniForm() {
            // Reset to first step
            alumniCurrentStep = 1;
            
            // Hide all steps except first
            alumniFormSteps.forEach((step, index) => {
                if (index === 0) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
            
            // Reset step indicators
            alumniStepIndicators.forEach((indicator, index) => {
                if (index === 0) {
                    indicator.classList.add('active');
                    indicator.classList.remove('completed');
                } else {
                    indicator.classList.remove('active', 'completed');
                }
            });
            
            // Reset button states
            document.getElementById('backToSelectionBtn').style.display = 'block';
            alumniBackButton.disabled = true;
            alumniNextButton.style.display = 'block';
            alumniSubmitButton.style.display = 'none';
            
            // Clear form validation errors
            document.querySelectorAll('#alumniForm .form-input.error').forEach(input => {
                input.classList.remove('error');
            });
        }

        // Close summary modal with X
        document.getElementById('closeSummaryModal').addEventListener('click', function() {
            document.getElementById('summaryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // PSGC API base URL
        const PSGC_API = "https://psgc.gitlab.io/api";

        // Elements
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');

        // Helper: Show loading or error
        function setSelectLoading(select, message = 'Loading...') {
            select.innerHTML = `<option value="">${message}</option>`;
            select.disabled = true;
        }
        function setSelectError(select, message = 'Failed to load') {
            select.innerHTML = `<option value="">${message}</option>`;
            select.disabled = true;
        }
        function setSelectReady(select) {
            select.disabled = false;
        }

        // Fetch and populate provinces
        setSelectLoading(provinceSelect);
        fetch(`${PSGC_API}/provinces/`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(provinces => {
                provinces.sort((a, b) => a.name.localeCompare(b.name));
                provinceSelect.innerHTML = '<option value="">Select Province</option>';
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.setAttribute('data-code', province.code);
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
                setSelectReady(provinceSelect);
            })
            .catch(() => {
                setSelectError(provinceSelect, 'Failed to load provinces');
            });

        // When province changes, fetch cities/municipalities
        provinceSelect.addEventListener('change', function() {
            setSelectLoading(citySelect);
            setSelectLoading(barangaySelect, 'Select Barangay');
            if (!this.value) {
                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                citySelect.disabled = false;
                barangaySelect.disabled = false;
                return;
            }

            const selectedOption = this.options[this.selectedIndex];
            const provinceCode = selectedOption.getAttribute('data-code');

            fetch(`${PSGC_API}/provinces/${provinceCode}/cities-municipalities/`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(cities => {
                    cities.sort((a, b) => a.name.localeCompare(b.name));
                    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.setAttribute('data-code', city.code);
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                    setSelectReady(citySelect);
                })
                .catch(() => {
                    setSelectError(citySelect, 'Failed to load cities');
                });
        });

        // When city changes, fetch barangays
        citySelect.addEventListener('change', function() {
            setSelectLoading(barangaySelect);
            if (!this.value) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                barangaySelect.disabled = false;
                return;
            }

            const selectedOption = this.options[this.selectedIndex];
            const cityCode = selectedOption.getAttribute('data-code');

            fetch(`${PSGC_API}/cities-municipalities/${cityCode}/barangays/`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(barangays => {
                    barangays.sort((a, b) => a.name.localeCompare(b.name));
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    barangays.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.name;
                        option.textContent = barangay.name;
                        barangaySelect.appendChild(option);
                    });
                    setSelectReady(barangaySelect);
                })
                .catch(() => {
                    setSelectError(barangaySelect, 'Failed to load barangays');
                });
        });

        document.querySelectorAll('#documentTypesGroup input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const qtyInput = this.parentElement.querySelector('.doc-qty');
                const minusBtn = this.parentElement.querySelector('.qty-btn.minus');
                const plusBtn = this.parentElement.querySelector('.qty-btn.plus');
                const quantitySelector = this.parentElement.querySelector('.quantity-selector');
                qtyInput.disabled = !this.checked;
                minusBtn.disabled = !this.checked;
                plusBtn.disabled = !this.checked;
                if (this.checked && !qtyInput.value) qtyInput.value = 1;
            });
        });

        // Plus/minus button logic
        document.querySelectorAll('.qty-btn.plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const qtyInput = this.parentElement.querySelector('.doc-qty');
                qtyInput.value = parseInt(qtyInput.value) + 1;
            });
        });
        document.querySelectorAll('.qty-btn.minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const qtyInput = this.parentElement.querySelector('.doc-qty');
                if (parseInt(qtyInput.value) > 1) {
                    qtyInput.value = parseInt(qtyInput.value) - 1;
                }
            });
        });
        
        // FAQ Data - Easy to add/edit questions and answers
        const faqData = {
            "How long does it take to process my document request?": "Processing times vary depending on the type of document requested. Typically, requests are processed within 3-5 business days. You'll receive an email notification when your documents are ready.",
            "What payment methods do you accept?": "We only accept cash payments. Payment must be made in person at our office when you pick up your documents. No online payments or bank transfers are available.",
            "Can I request multiple documents at once?": "Yes, you can request multiple documents in a single request. Simply select all the documents you need during the request process.",
            "How do I track my document request?": "After submitting your request, you'll receive a reference number via email. Use this number to track your request status through our tracking system.",
            "What if I need to cancel my request?": "Requests can be canceled within 24 hours of submission if processing hasn't begun. Please contact our support team immediately if you need to cancel your request."
        };

        // Chatbot Modal Logic
        const floatingChatbot = document.getElementById('floatingChatbot');
        const chatbotModal = document.getElementById('chatbotModal');
        const closeChatbotModal = document.getElementById('closeChatbotModal');
        const chatMessages = document.getElementById('chatMessages');
        const faqQuestions = document.getElementById('faqQuestions');
        
        if (floatingChatbot) {
            floatingChatbot.addEventListener('click', function() {
                chatbotModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                // Scroll to bottom of chat
                setTimeout(() => {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 100);
            });
        }
        
        if (closeChatbotModal) {
            closeChatbotModal.addEventListener('click', function() {
                chatbotModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }
        
        // FAQ Question Click Handler
        faqQuestions.addEventListener('click', function(e) {
            const questionBubble = e.target.closest('.faq-question-bubble');
            if (!questionBubble) return;
            
            const question = questionBubble.getAttribute('data-question');
            const answer = faqData[question];
            
            if (!answer) return;
            
            // Add user message
            addUserMessage(question);
            
            // Remove the question bubbles
            faqQuestions.style.display = 'none';
            
            // Show typing indicator
            showTypingIndicator();
            
            // Show bot response after delay
            setTimeout(() => {
                hideTypingIndicator();
                addBotMessage(answer);
                
                            // Show question bubbles again after a short delay
            setTimeout(() => {
                faqQuestions.style.display = 'flex';
            }, 1000);
            }, 1500);
        });
        
        // Add user message to chat
        function addUserMessage(message) {
            const userMessage = document.createElement('div');
            userMessage.className = 'message user-message';
            userMessage.innerHTML = `
                <div class="message-content">
                    <div class="message-bubble">
                        <p>${message}</p>
                    </div>
                </div>
            `;
            chatMessages.appendChild(userMessage);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Add bot message to chat
        function addBotMessage(message) {
            const botMessage = document.createElement('div');
            botMessage.className = 'message bot-message';
            botMessage.innerHTML = `
                <div class="message-content">
                    <div class="bot-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-bubble">
                        <p>${message}</p>
                    </div>
                </div>
            `;
            chatMessages.appendChild(botMessage);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Show typing indicator
        function showTypingIndicator() {
            const typingIndicator = document.createElement('div');
            typingIndicator.className = 'message bot-message';
            typingIndicator.id = 'typingIndicator';
            typingIndicator.innerHTML = `
                <div class="message-content">
                    <div class="bot-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;
            chatMessages.appendChild(typingIndicator);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Hide typing indicator
        function hideTypingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        // Alumni Document Type Checkboxes
        document.querySelectorAll('#alumniDocumentTypesGroup input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const qtyInput = this.parentElement.querySelector('.doc-qty');
                const minusBtn = this.parentElement.querySelector('.qty-btn.minus');
                const plusBtn = this.parentElement.querySelector('.qty-btn.plus');
                qtyInput.disabled = !this.checked;
                minusBtn.disabled = !this.checked;
                plusBtn.disabled = !this.checked;
                if (this.checked && !qtyInput.value) qtyInput.value = 1;
            });
        });

        // Check available document types for a user
        async function checkAvailableDocumentTypes(identifier, isAlumni) {
            if (!identifier) return;
            
            try {
                const response = await fetch('/check-available-documents', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        identifier: identifier,
                        is_alumni: isAlumni
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Cooldown check response:', data);
                    updateDocumentTypeAvailability(data.available_document_types, data.cooldown_information, isAlumni);
                }
            } catch (error) {
                console.error('Error checking available documents:', error);
            }
        }

        // Update document type availability in the UI
        function updateDocumentTypeAvailability(availableTypes, cooldownInfo, isAlumni) {
            console.log('Updating document availability:', {
                availableTypes: availableTypes,
                cooldownInfo: cooldownInfo,
                isAlumni: isAlumni
            });
            
            const formId = isAlumni ? 'alumniDocumentTypesGroup' : 'documentTypesGroup';
            const container = document.getElementById(formId);
            
            if (!container) return;
            
            // Update cooldown summary
            updateCooldownSummary(cooldownInfo, isAlumni);
            
            // Get all document type checkboxes
            const checkboxes = container.querySelectorAll('input[type="checkbox"]');
            
            checkboxes.forEach(checkbox => {
                const docType = checkbox.value;
                const isAvailable = availableTypes.includes(docType);
                const cooldownData = cooldownInfo[docType];
                
                if (isAvailable) {
                    // Enable the checkbox and show as available
                    checkbox.disabled = false;
                    checkbox.parentElement.style.opacity = '1';
                    checkbox.parentElement.style.filter = 'none';
                    
                    // Remove any cooldown styling
                    checkbox.parentElement.classList.remove('on-cooldown');
                    
                    // Update label to show it's available
                    const label = checkbox.parentElement.querySelector('label');
                    if (label) {
                        label.style.color = '#333';
                        if (cooldownData && cooldownData.last_request_date) {
                            label.textContent = `${docType} (Last requested: ${cooldownData.last_request_date})`;
                        } else {
                            label.textContent = docType;
                        }
                    }
                    
                    // Remove any cooldown info if it exists
                    const existingInfo = checkbox.parentElement.querySelector('.cooldown-info');
                    if (existingInfo) {
                        existingInfo.remove();
                    }
                } else {
                    // Disable the checkbox and show as on cooldown
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    checkbox.parentElement.style.opacity = '0.6';
                    checkbox.parentElement.style.filter = 'grayscale(0.5)';
                    
                    // Add cooldown styling
                    checkbox.parentElement.classList.add('on-cooldown');
                    
                    // Update label to show it's on cooldown
                    const label = checkbox.parentElement.querySelector('label');
                    if (label) {
                        label.style.color = '#999';
                        label.textContent = `${docType} (On Cooldown)`;
                    }
                    
                    // Add detailed cooldown info below the label
                    let cooldownInfo = checkbox.parentElement.querySelector('.cooldown-info');
                    if (!cooldownInfo) {
                        cooldownInfo = document.createElement('div');
                        cooldownInfo.className = 'cooldown-info';
                        cooldownInfo.style.cssText = 'font-size: 0.8rem; color: #e74c3c; margin-top: 5px; font-style: italic;';
                        checkbox.parentElement.appendChild(cooldownInfo);
                    }
                    
                    if (cooldownData && cooldownData.is_on_cooldown) {
                        cooldownInfo.innerHTML = `
                            <i class="fas fa-clock"></i> 
                            <strong>Cooldown Active:</strong> Last requested on ${cooldownData.last_request_date}. 
                            Available again in ${cooldownData.remaining_days} days.
                        `;
                    } else {
                        cooldownInfo.innerHTML = '<i class="fas fa-clock"></i> This document type is currently on cooldown. Please wait 40 days from your last request.';
                    }
                    
                    // Disable quantity selector
                    const qtyInput = checkbox.parentElement.querySelector('.doc-qty');
                    const minusBtn = checkbox.parentElement.querySelector('.qty-btn.minus');
                    const plusBtn = checkbox.parentElement.querySelector('.qty-btn.plus');
                    
                    if (qtyInput) qtyInput.disabled = true;
                    if (minusBtn) minusBtn.disabled = true;
                    if (plusBtn) plusBtn.disabled = true;
                }
            });
        }

        // Update cooldown summary section
        function updateCooldownSummary(cooldownInfo, isAlumni) {
            const summaryId = isAlumni ? 'alumniCooldownSummary' : 'cooldownSummary';
            const contentId = isAlumni ? 'alumniCooldownSummaryContent' : 'cooldownSummaryContent';
            
            const summaryContainer = document.getElementById(summaryId);
            const contentContainer = document.getElementById(contentId);
            
            if (!summaryContainer || !contentContainer) return;
            
            // Find document types on cooldown
            const onCooldown = [];
            Object.keys(cooldownInfo).forEach(docType => {
                if (cooldownInfo[docType].is_on_cooldown) {
                    onCooldown.push({
                        type: docType,
                        ...cooldownInfo[docType]
                    });
                }
            });
            
            if (onCooldown.length > 0) {
                // Show cooldown summary
                summaryContainer.style.display = 'block';
                
                let summaryHtml = '<div style="font-size: 0.9rem;">';
                onCooldown.forEach(item => {
                    const daysText = item.remaining_days === 1 ? 'day' : 'days';
                    summaryHtml += `
                        <div style="margin-bottom: 8px; padding: 8px; background: #fff; border-radius: 4px; border-left: 3px solid #ffc107;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong>${item.type}:</strong> Available again in ${item.remaining_days} ${daysText}
                                </div>
                                <div style="font-size: 0.8rem; color: #856404;">
                                    Last requested: ${item.last_request_date}
                                </div>
                            </div>
                        </div>
                    `;
                });
                summaryHtml += '<p style="margin-top: 10px; font-size: 0.8rem; color: #856404;"><i class="fas fa-lightbulb"></i> <strong>Tip:</strong> You can still request other document types that are not on cooldown!</p>';
                summaryHtml += '</div>';
                
                contentContainer.innerHTML = summaryHtml;
            } else {
                // Hide cooldown summary if no cooldowns
                summaryContainer.style.display = 'none';
            }
        }

        // Initialize Alumni PSGC Dropdowns
        function initializeAlumniPSGC() {
            const alumniProvinceSelect = document.getElementById('alumniProvince');
            const alumniCitySelect = document.getElementById('alumniCity');
            const alumniBarangaySelect = document.getElementById('alumniBarangay');

            // Helper functions for alumni PSGC
            function setAlumniSelectLoading(select, message = 'Loading...') {
                select.innerHTML = `<option value="">${message}</option>`;
                select.disabled = true;
            }
            function setAlumniSelectError(select, message = 'Failed to load') {
                select.innerHTML = `<option value="">${message}</option>`;
                select.disabled = true;
            }
            function setAlumniSelectReady(select) {
                select.disabled = false;
            }

            // Fetch and populate alumni provinces
            setAlumniSelectLoading(alumniProvinceSelect);
            fetch(`${PSGC_API}/provinces/`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(provinces => {
                    provinces.sort((a, b) => a.name.localeCompare(b.name));
                    alumniProvinceSelect.innerHTML = '<option value="">Select Province</option>';
                    provinces.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.name;
                        option.setAttribute('data-code', province.code);
                        option.textContent = province.name;
                        alumniProvinceSelect.appendChild(option);
                    });
                    setAlumniSelectReady(alumniProvinceSelect);
                })
                .catch(() => {
                    setAlumniSelectError(alumniProvinceSelect, 'Failed to load provinces');
                });

            // When alumni province changes, fetch cities/municipalities
            alumniProvinceSelect.addEventListener('change', function() {
                setAlumniSelectLoading(alumniCitySelect);
                setAlumniSelectLoading(alumniBarangaySelect, 'Select Barangay');
                if (!this.value) {
                    alumniCitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                    alumniBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    alumniCitySelect.disabled = false;
                    alumniBarangaySelect.disabled = false;
                    return;
                }

                const selectedOption = this.options[this.selectedIndex];
                const provinceCode = selectedOption.getAttribute('data-code');

                fetch(`${PSGC_API}/provinces/${provinceCode}/cities-municipalities/`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(cities => {
                        cities.sort((a, b) => a.name.localeCompare(b.name));
                        alumniCitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.setAttribute('data-code', city.code);
                            option.textContent = city.name;
                            alumniCitySelect.appendChild(option);
                        });
                        setAlumniSelectReady(alumniCitySelect);
                    })
                    .catch(() => {
                        setAlumniSelectError(alumniCitySelect, 'Failed to load cities');
                    });
            });

            // When alumni city changes, fetch barangays
            alumniCitySelect.addEventListener('change', function() {
                setAlumniSelectLoading(alumniBarangaySelect);
                if (!this.value) {
                    alumniBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    alumniBarangaySelect.disabled = false;
                    return;
                }

                const selectedOption = this.options[this.selectedIndex];
                const cityCode = selectedOption.getAttribute('data-code');

                fetch(`${PSGC_API}/cities-municipalities/${cityCode}/barangays/`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(barangays => {
                        barangays.sort((a, b) => a.name.localeCompare(b.name));
                        alumniBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        barangays.forEach(barangay => {
                            const option = document.createElement('option');
                            option.value = barangay.name;
                            option.textContent = barangay.name;
                            alumniBarangaySelect.appendChild(option);
                        });
                        setAlumniSelectReady(alumniBarangaySelect);
                    })
                    .catch(() => {
                        setAlumniSelectError(alumniBarangaySelect, 'Failed to load barangays');
                    });
            });
        }
    </script>
</body>
</html>