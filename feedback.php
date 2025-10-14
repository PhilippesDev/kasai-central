<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Partagez votre expérience de séjour au Kasaï-Central">
  <title>Feedback - Kasaï-Central Tourisme</title>
  
    <link rel="shortcut icon" href="images/favicon.jpg" type="image/x-icon">
  
    <script src="https://kit.fontawesome.com/3137461f7e.js" crossorigin="anonymous"></script>
  
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  
    <link rel="stylesheet" href="https://preline.co/assets/css/main.css?v=3.1.0">

  <style>
    
    #feedback-section {
      background: linear-gradient(90deg, rgba(37, 99, 235, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    
    .feedback-form {
      background: white;
      border-radius: 8px;
      padding: 2rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
    }

    .feedback-form .form-group {
      margin-bottom: 1.5rem;
    }

    .feedback-form label {
      font-weight: 500;
      color: #1f2937;
      display: block;
      margin-bottom: 0.5rem;
    }

    .feedback-form input,
    .feedback-form textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    .feedback-form input:focus,
    .feedback-form textarea:focus {
      outline: none;
      border-color: #2563eb;
    }

    .feedback-form textarea {
      resize: vertical;
      min-height: 100px;
    }

    .feedback-form button {
      background-color: #2563eb;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 4px;
      border: none;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .feedback-form button:hover {
      background-color: #1e40af;
    }

    
    .alert {
      padding: 1rem;
      border-radius: 4px;
      margin-bottom: 1.5rem;
      font-weight: 500;
    }

    .alert-success {
      background-color: #d1fae5;
      color: #065f46;
    }

    .alert-error {
      background-color: #fee2e2;
      color: #991b1b;
    }

    
    @media (max-width: 640px) {
      #feedback-section {
        min-height: 40vh;
        padding: 1rem;
      }

      .feedback-form {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body class="bg-blue-50 dark:bg-black" style="font-family: 'Quicksand', sans-serif;">
  
    <section id="feedback-section" class="relative">
    <div class="container">
      <div class="feedback-form">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Partagez votre expérience</h2>
        <p class="text-gray-600 mb-6">Racontez-nous votre magnifique séjour au Kasaï-Central !</p>

        <?php
                session_start();
        if (isset($_SESSION['success_message'])) {
          echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
          unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
          echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
          unset($_SESSION['error_message']);
        }
        ?>

        <form action="process_feedback.php" method="POST">
          <div class="form-group">
            <label for="name">Votre nom</label>
            <input type="text" id="name" name="name" required placeholder="Entrez votre nom">
          </div>
          <div class="form-group">
            <label for="email">Votre email</label>
            <input type="email" id="email" name="email" required placeholder="Entrez votre email">
          </div>
          <div class="form-group">
            <label for="feedback">Votre expérience</label>
            <textarea id="feedback" name="feedback" required placeholder="Décrivez votre séjour..."></textarea>
          </div>
          <button type="submit">Envoyer</button>
        </form>
      </div>
    </div>
  </section>

    <script src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>
</body>
</html>