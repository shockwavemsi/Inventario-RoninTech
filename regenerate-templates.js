import { Document, Packer, Paragraph, AlignmentType } from 'docx';
import fs from 'fs';

const facuraDoc = new Document({
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
                text: "Factura Nº: {d.numero_factura}"
            }),
            new Paragraph({
                text: "Fecha: {d.fecha}"
            }),
            new Paragraph({
                text: "Proveedor: {d.proveedor}"
            }),
            new Paragraph({ text: "" }),
            new Paragraph({
                text: "Total: {d.total}"
            }),
            new Paragraph({
                text: "Método de Pago: {d.metodo_pago}"
            })
        ]
    }]
});

Packer.toBuffer(facuraDoc).then(buffer => {
    fs.writeFileSync('resources/views/carbone/factura.docx', buffer);
    console.log('✅ factura.docx regenerado correctamente');
    process.exit(0);
});
