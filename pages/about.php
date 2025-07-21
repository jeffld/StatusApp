<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StatusApp Help</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content {
      margin-left: 250px;
      padding: 20px;
    }
    pre {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="d-flex">
  <?php include '../sidebar.php'; ?>
<!-- about.php or about.html -->
<div class="container mt-5">
  <h1 class="mb-4">About StatusApp</h1>

  <p><strong>StatusApp</strong> is an internal web application designed to streamline and organize checklist-based workflows for our company. Built with security and simplicity in mind, it allows each department to focus on tasks relevant to their role—reducing noise, improving efficiency, and promoting accountability.</p>

  <hr class="my-4">

  <h3>🎯 Purpose</h3>
  <ul>
    <li>Centralize onboarding processes for employees, clients, and vendors</li>
    <li>Enforce role-based task separation to reduce errors and distractions</li>
    <li>Track task completion and ensure compliance with internal procedures</li>
    <li>Leverage internal user authentication for seamless login and auditing</li>
    <li>Lay the groundwork for future AI assistant integration via Ollama</li>
  </ul>

  <hr class="my-4">

  <h3>✨ Key Features</h3>
  <ul>
    <li><strong>Checklist Management</strong> – Create and manage detailed checklists for any process</li>
    <li><strong>Role-Based Access</strong> – Each role (IT, HR, Customer Service, etc.) only sees relevant checklists</li>
    <li><strong>Audit Logging</strong> – Tracks user actions for accountability and historical reference</li>
    <li><strong>Bootstrap UI</strong> – Clean, responsive interface optimized for desktop intranet users</li>
    <li><strong>Windows Auth Integration</strong> – Automatically identifies users via intranet credentials</li>
    <li><strong>Modular Design</strong> – Easy to extend with new roles, checklists, or task types</li>
    <li><strong>AI-Ready</strong> – Future integration with Ollama for task suggestions, summaries, and automation</li>
  </ul>

  <hr class="my-4">

  <h3>🏢 Real-World Examples (StatusApp)</h3>
  <table class="table table-bordered">
    <thead class="thead-light">
      <tr>
        <th>Role</th>
        <th>Checklist Access</th>
        <th>Hidden From</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Customer Service</strong></td>
        <td>Client Welcome Packet, Voicemail Setup, Phone Routing</td>
        <td>Firewall Setup, VPN Credentials, Domain Controller Setup</td>
      </tr>
      <tr>
        <td><strong>IT</strong></td>
        <td>New Employee Setup, Email Configuration, Device Imaging</td>
        <td>HR Forms, Client Welcome Checklist, Payroll Onboarding</td>
      </tr>
      <tr>
        <td><strong>HR</strong></td>
        <td>I-9 Forms, Policy Acknowledgments, Benefits Enrollment</td>
        <td>Technical Configuration, SNMP Alert Setup</td>
      </tr>
    </tbody>
  </table>

  <hr class="my-4">

  <p class="text-muted"><small>StatusApp was developed in-house to reflect our real-world processes and our team's commitment to clarity, control, and continual improvement.</small></p>
</div>
</div>
</body>
</html>
