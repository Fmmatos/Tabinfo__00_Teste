
    // GERAÇÃO DE CPF VÁLIDO
        export function gerarCPFValido(): string {
            // PRIMEIROS 9 DÍGITOS
                const digitos: number[] = [];
                for (let i = 0; i < 9; i++) {
                    digitos.push(Math.floor(Math.random() * 10));
                }
            // PRIMEIROS 9 DÍGITOS

            // PRIMEIRO DÍGITO VERIFICADOR
                let soma = 0;
                for (let i = 0; i < 9; i++) {
                    soma += digitos[i] * (10 - i);
                }
                let primeiroDigito = 11 - (soma % 11);
                if (primeiroDigito >= 10) primeiroDigito = 0;
                digitos.push(primeiroDigito);
            // PRIMEIRO DÍGITO VERIFICADOR
            
            // SEGUNDO DÍGITO VERIFICADOR
                soma = 0;
                for (let i = 0; i < 10; i++) {
                    soma += digitos[i] * (11 - i);
                }
                let segundoDigito = 11 - (soma % 11);
                if (segundoDigito >= 10) segundoDigito = 0;
                digitos.push(segundoDigito);
            // SEGUNDO DÍGITO VERIFICADOR
            
            return digitos.join('');
        }
    // GERAÇÃO DE CPF VÁLIDO





    // GERAÇÃO DE TELEFONE ALEATÓRIO
        export function gerarTelefoneAleatorio(): string {
            return '11' + Math.floor(Math.random() * 900000000 + 100000000).toString();
        }
    // GERAÇÃO DE TELEFONE ALEATÓRIO





    // GERAÇÃO DE DATA DE NASCIMENTO ALEATÓRIA
        export function gerarDataNascimentoAleatoria(): string {
            const year = Math.floor(Math.random() * 36) + 1970;
            const month = String(Math.floor(Math.random() * 12) + 1).padStart(2, '0');
            const day = String(Math.floor(Math.random() * 28) + 1).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    // GERAÇÃO DE DATA DE NASCIMENTO ALEATÓRIA





    // GERAÇÃO DE STRING ALEATÓRIA
        export function gerarStringAleatoria(tamanho: number = 8): string {
            return Math.random().toString(36).substring(2, 2 + tamanho);
        }
    // GERAÇÃO DE STRING ALEATÓRIA
