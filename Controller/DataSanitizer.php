<?php
namespace Controller;

abstract class DataSanitizer
{
    protected function sanitizeString(?string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    protected function sanitizeInt($value): int
    {
        return filter_var($value, FILTER_VALIDATE_INT) ?? 0;
    }

    protected function sanitizeDate(?string $value): ?string
    {
        if (!$value) return null;
        $date = date_create($value);
        return $date ? date_format($date, 'Y-m-d') : null;
    }

    protected function sanitizeImage(?string $value): ?string
    {
        if (!$value) return null;
        return preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $value);
    }

    public function sanitizePieza(array $data): array
    {
        return [
            'idPieza' => $this->sanitizeInt($data['idPieza'] ?? 0),
            'num_inventario' => $this->sanitizeString($data['num_inventario'] ?? ''),
            'especie' => $this->sanitizeString($data['especie'] ?? ''),
            'estado_conservacion' => $this->sanitizeString($data['estado_conservacion'] ?? ''),
            'fecha_ingreso' => $this->sanitizeDate($data['fecha_ingreso'] ?? null),
            'cantidad_de_piezas' => $this->sanitizeString($data['cantidad_de_piezas'] ?? ''),
            'clasificacion' => $this->sanitizeString($data['clasificacion'] ?? ''),
            'observacion' => $this->sanitizeString($data['observacion'] ?? ''),
            'imagen' => $this->sanitizeImage($data['imagen'] ?? null),
            'Donante_idDonante' => $this->sanitizeInt($data['Donante_idDonante'] ?? 0)
        ];
    }

    abstract protected function validate(array $data): bool;
}
