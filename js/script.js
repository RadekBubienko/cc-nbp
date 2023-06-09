{
  const welcome = () => {
    console.log("Hello Developers! Nice to see You!");
  };

  // Nasłuchuj kliknięcie przycisku o klasie 'js-clear_button'
  document.addEventListener("DOMContentLoaded", function () {
    const clearButton = document.querySelector(".js-clear_button");
    if (clearButton) {
      clearButton.addEventListener("click", clearDivs);
    }
  });

  function clearDivs() {
    document.getElementById("input1").value = "";
    document.getElementById("select1").value = "wybierz walutę";
    document.getElementById("select2").value = "wybierz walutę";

    const errorElements = document.getElementsByClassName("error");
    while (errorElements.length > 0) {
      errorElements[0].parentNode.removeChild(errorElements[0]);
    }

    // Usuń elementy o klasie 'success'
    const successElements = document.getElementsByClassName("success");
    while (successElements.length > 0) {
      successElements[0].parentNode.removeChild(successElements[0]);
    }

    // Usuń element o klasie 'result'
    const resultElement = document.querySelector(".result");
    if (resultElement) {
      resultElement.parentNode.removeChild(resultElement);
    }
  }
  
  welcome();
}
