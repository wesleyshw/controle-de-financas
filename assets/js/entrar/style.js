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
