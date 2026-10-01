<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

require 'controllers/SiteController.php';
require 'controllers/PatientController.php';
require 'controllers/AdminController.php';

$siteController = new SiteController();
$patientController = new PatientController();
$adminController = new AdminController();

$page = $_GET["page"] ?? "home";

// --- Pages publiques (front office) ---
if ($page === "home")
    $siteController->home();
else if ($page === "services")
    $siteController->services();
else if ($page === "about")
    $siteController->about();
else if ($page === "news")
    $siteController->news();
else if ($page === "appointment")
    $siteController->appointment();
else if ($page === "appointment-valid")
    $siteController->appointmentValid();
else if ($page === "news-detail")
    $siteController->newsDetail();

// --- Comptes patients ---
else if ($page === "register")
    $patientController->register();
else if ($page === "register-valid")
    $patientController->registerValid();
else if ($page === "login")
    $patientController->login();
else if ($page === "login-valid")
    $patientController->loginValid();
else if ($page === "logout")
    $patientController->logout();
else if ($page === "my-appointments")
    $patientController->myAppointments();

// --- Connexion de l'equipe  ---
else if ($page === "admin-login")
    $adminController->adminLogin();
else if ($page === "admin-login-valid")
    $adminController->adminLoginValid();
else if ($page === "admin-logout")
    $adminController->adminLogout();

// --- Back office (Dr. Dupont / equipe) ---
else if ($page === "admin")
    $adminController->admin();
else if ($page === "admin-appointments")
    $adminController->adminAppointments();
else if ($page === "confirm-appointment")
    $adminController->confirmAppointment();
else if ($page === "cancel-appointment")
    $adminController->cancelAppointment();
else if ($page === "admin-services")
    $adminController->adminServices();
else if ($page === "create-service")
    $adminController->createService();
else if ($page === "create-service-valid")
    $adminController->createServiceValid();
else if ($page === "delete-service")
    $adminController->deleteService();
else if ($page === "admin-about")
    $adminController->adminAbout();
else if ($page === "admin-about-valid")
    $adminController->adminAboutValid();
else if ($page === "admin-news")
    $adminController->adminNews();
else if ($page === "create-news")
    $adminController->createNews();
else if ($page === "create-news-valid")
    $adminController->createNewsValid();
else if ($page === "delete-news")
    $adminController->deleteNews();
else if ($page === "admin-staff")
    $adminController->adminStaff();
else if ($page === "create-staff")
    $adminController->createStaff();
else if ($page === "create-staff-valid")
    $adminController->createStaffValid();
else if ($page === "edit-appointment")
    $adminController->editAppointment();
else if ($page === "edit-appointment-valid")
    $adminController->editAppointmentValid();
else if ($page === "edit-service")
    $adminController->editService();
else if ($page === "edit-service-valid")
    $adminController->editServiceValid();
else if ($page === "edit-news")
    $adminController->editNews();
else if ($page === "edit-news-valid")
    $adminController->editNewsValid();
else if ($page === "admin-patients")
    $adminController->adminPatients();
else if ($page === "create-patient")
    $adminController->createPatient();
else if ($page === "create-patient-valid")
    $adminController->createPatientValid();
else if ($page === "edit-patient")
    $adminController->editPatient();
else if ($page === "edit-patient-valid")
    $adminController->editPatientValid();
else if ($page === "delete-patient")
    $adminController->deletePatient();
else if ($page === "admin-horaires")
    $adminController->adminHoraires();
else if ($page === "admin-horaires-valid")
    $adminController->adminHorairesValid();
else if ($page === "admin-home")
    $adminController->adminHome();
else if ($page === "admin-home-valid")
    $adminController->adminHomeValid();
