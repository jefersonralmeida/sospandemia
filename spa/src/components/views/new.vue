<template>
  <div class="form-group">
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
      <div class="col-md-5 p-lg-5 mx-auto my-5">
        <h1 class="display-5 font-weight-normal">Primeiros Passos!</h1>
        <p class="lead font-weight-normal">Gostaria de cadastrar minha entidade para receber ajuda.</p>
      </div>
    </div>

    <div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
      <div class="bg-light mr-md- pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
        <div class="my-3 p-3">
          <h2 class="display-5">
            1 - Clique no menu:
            <span class="font-italic">Registrar</span>
          </h2>
          <br />
          <p>
            <img class="mb-2" src="../../assets/new/registro.png" height="100%" />
          </p>
        </div>
        
      </div>
    </div>
    <div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
      <div class="bg-light mr-md- pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
        <div class="my-3 p-3">
          <h2 class="display-5">2 - Preencha as os campos abaixo conforme indicado:</h2>
          <br />
          <p>
            <img class="mb-2" src="../../assets/new/usuario.png" height="100%" />
          </p>
        </div>
      </div>
    </div>
    <div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
      <div class="bg-light mr-md- pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
        <div class="my-3 p-3">
          <h2 class="display-5">3 - Fique atento a mensagem!</h2>
          <br />
          <p>
            <img class="mb-2" src="../../assets/new/cofirma_email.png" height="100%" />
          </p>
          <br>
          <p>Caso o email não apareça em sua caixa de entrada, verifique se não está em sua caixa de Spam.</p>
          <br>
          <p>Em sua caixa de email, deve aparecer um email conforme mostrado abaixo:</p>
          <br>
          <p>
            <img class="mb-2" src="../../assets/new/email_recebido.png" height="100%" />
          </p>
          <br>
          <p>O conteúdo do email é mostrado na figura abaixo:</p>
          <br>
          <p>
            <img class="mb-2" src="../../assets/new/conteudo_email.png" height="100%" />
          </p>
          <br>
          <p> Clique em: Verifique o endereço de e-mail</p>          
        </div>
        <div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
      <div class="bg-light mr-md- pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
        <div class="my-3 p-3">
          <h2 class="display-5">4 - Clique em Selecionar Entidade. Em seguida, clique em Gerenciar Entidades:</h2>
          <br />
          <p>
            <img class="mb-2" src="../../assets/new/gerenciar_entidade.png" height="100%" />
          </p>
        </div>
      </div>
    </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.container {
  max-width: 960px;
}

/*
 * Custom translucent site header
 */

.site-header {
  background-color: rgba(0, 0, 0, 0.85);
  -webkit-backdrop-filter: saturate(180%) blur(20px);
  backdrop-filter: saturate(180%) blur(20px);
}
.site-header a {
  color: #999;
  transition: ease-in-out color 0.15s;
}
.site-header a:hover {
  color: #fff;
  text-decoration: none;
}

/*
 * Dummy devices (replace them with your own or something else entirely!)
 */

.product-device {
  position: absolute;
  right: 10%;
  bottom: -30%;
  width: 300px;
  height: 540px;
  background-color: #333;
  border-radius: 21px;
  -webkit-transform: rotate(30deg);
  transform: rotate(30deg);
}

.product-device::before {
  position: absolute;
  top: 10%;
  right: 10px;
  bottom: 10%;
  left: 10px;
  content: "";
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 5px;
}

.product-device-2 {
  top: -25%;
  right: auto;
  bottom: 0;
  left: 5%;
  background-color: #e5e5e5;
}

/*
 * Extra utilities
 */

.flex-equal > * {
  -ms-flex: 1;
  flex: 1;
}
@media (min-width: 768px) {
  .flex-md-equal > * {
    -ms-flex: 1;
    flex: 1;
  }
}

.overflow-hidden {
  overflow: hidden;
}
</style>