<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Commander votre Pizza</title>
    <script type="text/javascript">
    // function recherchePizza(){
      // document.getElementById('selectPizza').innerHTML = "";
      const xhr = new XMLHttpRequest();
      // var recherche = document.getElementById('pizza').value;
      // console.log("Recherche: " + recherche);
      // xhr.open('GET', 'http://127.0.0.1:8080/Test/api/index.php?nom=' + recherche);
      xhr.open('POST', 'http://127.0.0.1:8080/Test/api/liste-pizzas.php');
      xhr.send();
      xhr.addEventListener('readystatechange', function() {
        if (xhr.readyState === XMLHttpRequest.LOADING) {
          console.log("Chargement...");
        }else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          console.log("Ok !");

          var response = JSON.parse(xhr.responseText);

          var nbResults = 0;
          for (var value in response.results) {
            nbResults++;
          }
          nbResults--;
          // for (let i = 1; i < nbResults+1; i++) {
          //   document.getElementById('resultats').innerHTML += "<li>" + response.results[i].nom + "<br /><em>" + response.results[i].contenu + "</em><button>Commander</button></li>";
          // }
          var a = "";
          for (let i = 1; i < nbResults+1; i++) {
            a += "<option value='" + response.results[i].nom + "'>" + response.results[i].nom + "</option>";
          }
          document.getElementById('selectPizza').innerHTML += a;
          // if (nbResults === 0) {
          //   document.getElementById('resultats').innerHTML += "<strong>Aucune pizza trouvée</strong>";
          // }
        }
      });
    </script>
  </head>
  <body>
    <h1>Commander votre pizza</h1>
    <form class="" action="ajouter-commande.php" method="post">
      <input type="text" name="personne" id="pizza" placeholder="Votre nom">
      <select id="selectPizza" name="produit"></select>
      <input type="submit" name="commander" value="Commander">
    </form>
  </body>
</html>
