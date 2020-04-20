const rules = {
    data(){
        return {
            rules: {
                required: value => !!value || "Obrigatório.",
                min: (v, num = 8) => v != null && v.length >= num || `Minimo ${num} caracteres`,
                max: (v, num = 100) => v != null && v.length <= num || `Máximo ${num} caracteres`,
                numberRule: v => {
                    const regexNumbers = new RegExp(/^-?[0-9]*$/)
                    if(!regexNumbers.test(parseFloat(v))) return "O campo deve conter apenas números."
                    if(v <= 0) return "O campo deve conter valores maiores que zero."
                    return true
                }
            }
        }
    }
}
export default rules;