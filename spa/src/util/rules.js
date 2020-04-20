const rules = {
    data(){
        return {
            rules: {
                required: value => !!value || "Obrigatório.",
                min: (v, num = 8) => v != null && v.length >= num || `Minimo ${num} caracteres`,
                max: (v, num = 100) => v != null && v.length <= num || `Máximo ${num} caracteres`,
                numberRule: v => {
                    if(v === "") return "O campo deve conter apenas números inteiros." //Vuetify tendo problemas com forms tipo 'text' quando o campo possue carácteres como '.' ou 'e'
                    const regexNumbers = new RegExp(/-?[0-9]*/)
                    let match = v.toString().match(regexNumbers)
                    if(!(match && match[0] === v)) return "O campo deve conter apenas números inteiros."
                    if(v <= 0) return "O campo deve conter valores maiores que zero."
                    return true
                }
            }
        }
    }
}
export default rules;