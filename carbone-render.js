import carbone from 'carbone';
import fs from 'fs';

const [templatePath, outputPath, dataJson] = process.argv.slice(2);

try {
    const data = JSON.parse(dataJson);

    // ✅ DEBUG: Ver qué datos recibe
    console.error('📋 Datos recibidos:', JSON.stringify(data, null, 2));
    console.error('📄 Template:', templatePath);
    console.error('💾 Output:', outputPath);

    carbone.render(templatePath, data, (err, result) => {
        if (err) {
            console.error('❌ Error Carbone:', err.message);
            process.exit(1);
        }

        fs.writeFileSync(outputPath, result);
        console.log(`✅ DOCX generado: ${outputPath}`);
        process.exit(0);
    });
} catch (error) {
    console.error('❌ Error:', error.message);
    process.exit(1);
}