import { Document, Packer, Paragraph, AlignmentType } from 'docx';
import fs from 'fs';

const doc = new Document({
    sections: [{
        children: [
            new Paragraph({
                text: "GRUPO JPG S.A.",
                bold: true,
                fontSize: 28,
                alignment: AlignmentType.CENTER
            }),
            new Paragraph({
                text: "FACTURA",
                bold: true,
                fontSize: 20,
                alignment: AlignmentType.CENTER
            }),
            new Paragraph({ text: "" }),
            new Paragraph({
                text: "Factura Nº: {numero_factura}",
                fontSize: 14,
                bold: true
            }),
            new Paragraph({
                text: "Fecha: {fecha}",
                fontSize: 12
            }),
            new Paragraph({
                text: "Proveedor: {proveedor}",
                fontSize: 12
            }),
            new Paragraph({ text: "" }),
            new Paragraph({
                text: "Total: {total}",
                fontSize: 14,
                bold: true
            }),
            new Paragraph({
                text: "Método de Pago: {metodo_pago}",
                fontSize: 12
            })
        ]
    }]
});

Packer.toBuffer(doc).then(buffer => {
    fs.writeFileSync('resources/views/carbone/factura.docx', buffer);
    console.log('✅ factura.docx creado automáticamente');
});