// This function sets default values and then calls calcularTotais
function iniciarCalculo() {
  // Example system-provided values (replace with actual values from your system)
  const valesDoSistema = 0;
  const saidasDoSistema = 0;
  const pagamentosProfissionaisDoSistema = 850; // Example system value for professionals

  // Set the default for pagamentosProfissionais if the input is empty
  const pagamentosInput = document.getElementById("pagamentosProfissionais");
  if (
    !pagamentosInput.value &&
    pagamentosProfissionaisDoSistema !== undefined
  ) {
    pagamentosInput.value = pagamentosProfissionaisDoSistema;
  }

  // Now call the calculation function with system-provided vales and saidas
  calcularTotais(valesDoSistema, saidasDoSistema);
}

function calcularTotais(valesDoSistema, saidasDoSistema) {
  const caixa = parseFloat(document.getElementById("caixa").value) || 0;
  const cartao = parseFloat(document.getElementById("cartao").value) || 0;
  // Get payments from the input field (user's input or default set by iniciarCalculo)
  const pagamentos =
    parseFloat(document.getElementById("pagamentosProfissionais").value) || 0;

  // Use the values provided by the system for vales and saidas
  const vales = valesDoSistema || 0;
  const saidas = saidasDoSistema || 0;

  // Corrected: should be sum for entries
  const totalDescontos = cartao + vales + saidas + pagamentos;

  const saldoFinal = caixa - totalDescontos;

  document.getElementById(
    "resultadoFinal"
  ).innerText = `Saldo Caixa Final: R$ ${saldoFinal.toFixed(2)}`;
}

// Call iniciarCalculo once the page loads to set initial default values if any
window.onload = iniciarCalculo;
