const validation = {
    methods: {
        handleResponseError(error, form) {
            console.log(this.$refs)
            for (let errPropriety in error.response.data.errors) {
                try {
                    form[errPropriety].errorMessages.push(
                        error.response.data.errors[errPropriety][0]
                    );
                } catch (e) {
                    console.log(e)
                    console.warn(`Não foi possivel encontrar a propriedade \'${errPropriety}\' no formulario.`)
                }
            }
        }
    }
}
export default validation;