<?php

namespace App\Filament\Resources\BalanceVacations\Schemas;

use App\Models\Employee;
use App\Models\Payroll;
use App\States\EmployeeStatus;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BalanceVacationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            // Este formulario solo es visual, solo es para extraer los datos que haran el balance.
            ->components([
                //Insformacion personal del empleado
                Grid::make(1)
                    ->schema([
                        Section::make('Personal Info')
                            ->columns(1)
                            ->schema([
                                Select::make('employee_id')
                                    ->relationship('employee', 'first_name', fn($query) => $query
                                        ->where('employee_status', EmployeeStatus::Active))
                                    ->getOptionLabelFromRecordUsing(
                                        fn($record) =>
                                        $record->first_name . ' ' . $record->last_name
                                    )
                                    ->label('Nombre Empleado')
                                    ->reactive()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->validationMessages([])
                                    //Este metodo es para traer los datos de la tabla de employee al formulario de balance_vacation
                                    ->afterStateUpdated(function ($state, callable $set) {

                                        $employee = Employee::find($state);

                                        if ($employee) {
                                            $set('identity_number', $employee->identity_number);
                                            $set('address_number', $employee->address_number);
                                            $set('hiring_date', $employee->hiring_date);
                                            $set('anniversary_date', $employee->anniversary_date);
                                            $set('employee_status', $employee->employee_status);
                                            $set('department_id', $employee->department_id);
                                            $set('payroll_id', $employee->payroll_id);
                                            $set('user_id', $employee->user_id);
                                        }

                                        // Calcular vacaciones
                                        $total = self::calculateAccruedTotal($state);
                                        $thisYear = self::calculateAccruedThisYear($employee);

                                        $set('accrued_total', $total);
                                        $set('accrued_this_year', $thisYear);

                                        // Reiniciar usadas y recalcular balance
                                        $set('used', 0);
                                        $set('balance', $total);

                                        $balance = $total; // al cargar el empleado
                                        $pendings = $balance - $thisYear;
                                        $set('pendings', $pendings);
                                    }),

                                TextInput::make('identity_number')
                                    ->label('Numero de Identidad')
                                    ->hiddenOn('edit')
                                    ->disabled()
                                    ->dehydrated(false), // para que el valor del campo no se envie ni se guarde en la base de datos (temporal)
                                TextInput::make('address_number')
                                    ->label('Numero de Direccion')
                                    ->hiddenOn('edit')
                                    ->disabled()
                                    ->dehydrated(false),
                                DatePicker::make('hiring_date')
                                    ->label('Fecha de Contratacion')
                                    ->disabled()
                                    ->hiddenOn('edit')
                                    ->dehydrated(false),
                                DatePicker::make('anniversary_date')
                                    ->label('Fecha de Aniversario')
                                    ->disabled()
                                    ->hiddenOn('edit')
                                    ->dehydrated(false),
                                Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->label('Departamento')
                                    ->disabled()
                                    ->hiddenOn('edit')
                                    ->dehydrated(false),
                                Select::make('employee_status')
                                    ->label('Estado de Empleado')
                                    ->options(EmployeeStatus::class)
                                    ->disabled()
                                    ->hiddenOn('edit')
                                    ->dehydrated(false),
                                Select::make('payroll_id')
                                    ->label('Tipo de Nomina')
                                    ->relationship('payroll', 'payroll_type')
                                    ->disabled()
                                    ->hiddenOn('edit')
                                    ->dehydrated(false),
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Usuario')
                                    ->hiddenOn('edit')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ]),

                Grid::make(1)
                    ->schema([
                        Section::make('Balance Info')
                            ->columns(1)
                            ->schema([
                                //Total acmumulado
                                TextInput::make('accrued_total')
                                    ->readOnly()
                                    ->default(0)
                                    ->label('Total Acumulado'),

                                //Acumulado este año
                                TextInput::make('accrued_this_year')
                                    ->readOnly()
                                    ->default(0)
                                    ->label('Acumulado este Año'),

                                //Vacaciones usadas
                                TextInput::make('used')
                                    ->reactive()
                                    ->default(0)
                                    ->label('Vacaciones Usadas')
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $total = (int) ($get('accrued_total') ?? 0);
                                        $used = (int) $state;

                                        // Balance contable (puede ser negativo si excede)
                                        $balance = $total - $used;

                                        $set('balance', $balance);

                                        //calcula las vacaciones pendientes por gozar 
                                        $accruedThisYear = (int) ($get('accrued_this_year') ?? 0);
                                        $pendings = $balance - $accruedThisYear;
                                        $set('pendings', $pendings);
                                    }),

                                //Balance
                                TextInput::make('balance')
                                    ->default(0)
                                    ->readOnly()
                                    ->label('Balance'),

                                //Pednientes de Gozar
                                TextInput::make('pendings')
                                    ->default(0)
                                    ->readOnly()
                                    ->label('Pendientes de Gozar'),
                            ]),

                        Section::make('Notas')
                            ->columns(1)
                            ->schema([
                                Textarea::make('notes')
                                    ->label('Descripcion de vacaciones usadas')
                                    ->autosize()
                            ])
                    ]),
            ]);
    }

    //Metodos para hacer los calculos
    //Metodo que va a hacer el calculo del acumulado total 
    public static function calculateAccruedTotal($employee_id)
    {
        if (!$employee_id) return 0;

        $employee_id = Employee::find($employee_id);
        if (!$employee_id || !$employee_id->payroll_id) return 0;

        $payroll = Payroll::find($employee_id->payroll_id);
        if (!$payroll) return 0;

        $yearsWorked = Carbon::parse($employee_id->hiring_date)->diffInYears(now());

        return (int) round($yearsWorked * ($payroll->vacations_days ?? 0));
    }

    // Metodo para hacer el calculo de acumulado este año
    // Vacaciones acumuladas este año (proporcional exacta)
    public static function calculateAccruedThisYear(Employee $employee)

    {
        if (!$employee || !$employee->hiring_date) return 0;

        $hiringDate = Carbon::parse($employee->hiring_date);
        $today = Carbon::today();

        // Obtener el aniversario de este año
        $anniversaryThisYear = $hiringDate->copy()->year($today->year);

        // Si aún no ha llegado el aniversario, usar el del año pasado
        if ($today->lt($anniversaryThisYear)) {
            $anniversaryThisYear->subYear();
        }

        // Días desde el último aniversario (equivalente a "YD")
        $days = $anniversaryThisYear->diffInDays($today);

        // Fórmula tipo Excel
        $vacationDays = ($days / 30) * 1.83;

        return (int) round($vacationDays);
    }
}
