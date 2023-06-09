{
  const welcome = () => {
    console.log("Hello Developers! Nice to see You!");
  }

  function clearInput() {
    document.getElementById("input1").value = "";
    document.getElementById("select1").value = "wybierz walutę";
    document.getElementById("select2").value = "wybierz walutę";

    const displayResult = document.querySelector(".js-result");
    displayResult.remove();

    const buttonToRemove = document.querySelector(".js-save_calculation");
    buttonToRemove.remove();

    const errorElements = document.getElementsByClassName("error");
    while (errorElements.length > 0) {
      errorElements[0].parentNode.removeChild(errorElements[0]);
    }

    // Usuń elementy o klasie 'success'
    const successElements = document.getElementsByClassName("success");
    while (successElements.length > 0) {
      successElements[0].parentNode.removeChild(successElements[0]);
    }
  }
}
