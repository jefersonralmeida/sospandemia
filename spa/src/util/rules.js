const rules = {
    data(){
        return {
            rules: {
                required: value => !!value || "Obrigatório.",
                min: (v, num = 8) => v != null && v.length >= num || `Minimo ${num} caracteres`,
                max: (v, num = 100) => v != null && v.length <= num || `Máximo ${num} caracteres`,
                numberRule: v => (parseInt(v) && v >= 1) || "O campo deve conter apenas números."
            }
        }
    }
}
export default rules;