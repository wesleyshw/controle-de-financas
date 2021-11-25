$(function () {
  $("form[name='login']").validate({
    rules: {

      email: {
        required: true,
        email: true
      },
      password: {
        required: true,

      }
    },
    messages: {
      email: "Digite um e-mail válido",

      password: {
        required: "Digite uma senha",

      }

    },
    submitHandler: function (form) {
      form.submit();
    }
  });
});



$(function () {

  $("form[name='registration']").validate({
    rules: {
      name: "required",
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 5
      }
    },

    messages: {
      firstname: "Por favor preencha o nome de seu Comércio.",
      password: {
        required: "Preencha a senha.",
        minlength: "Sua senha deve ter pelo menos 5 caracteres."
      },
      confpassword: {
        required: "Preencha a senha.",
        minlength: "Sua senha deve ter pelo menos 5 caracteres."
      },
      email: "Preencha um e-mail válido."
    },

    submitHandler: function (form) {
      form.submit();
    }
  });
});
