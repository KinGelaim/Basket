unit Unit1;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms, Vcl.Dialogs, Vcl.StdCtrls, ActiveX;

type
  TForm1 = class(TForm)
    Button1: TButton;
    Button2: TButton;
    Button3: TButton;
    Button4: TButton;
    Button5: TButton;
    procedure Button1Click(Sender: TObject);
    procedure Button2Click(Sender: TObject);
    procedure Button3Click(Sender: TObject);
    procedure Button4Click(Sender: TObject);
    procedure Button5Click(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  Form1: TForm1;

implementation
uses TestArrayDLL_TLB;
{$R *.dfm}

// Создание PSafeArray и вывод на экран
procedure TForm1.Button2Click(Sender: TObject);
var
  i: Integer;
  d: Double;

  dataLength: Integer;
  matrix: Variant;

  psaBeginMatrix: PSafeArray;
  lBound, hBound: Integer;
begin
  // Создаём массив Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // Заполняем массив
  for i := 0 to dataLength-1 do
    matrix[i] := i * 3;

  // Преобразуем массив в PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // Вытаскиваем нижнию и верхнию границу
  SafeArrayGetLBound(psaBeginMatrix, 1, lBound);
  SafeArrayGetUBound(psaBeginMatrix, 1, hBound);

  // Вытаскиваем
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(psaBeginMatrix, i, d);
    ShowMessage(d.ToString());
  end;

  // Очищаем матрицу
  VarClear(matrix);

  // Очищаем PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
end;

// Получение PSafeArray из DLL
procedure TForm1.Button1Click(Sender: TObject);
var
  TestArray: TArrayDLL;
  getData: PSafeArray;

  i, lBound, hBound: Integer;
  d: Integer;
begin
  // Создаём объект для вызова DLL
  TestArray := TArrayDLL.Create(nil);

  // Вытаскиваем PSafeArray из DLL [4;3;7]
  getData := testArray.GetIntArray();

  // Освобождаем объект
  TestArray.Free;

  // Вытаскиваем нижнию и верхнию границу
  SafeArrayGetLBound(getData, 1, lBound);
  SafeArrayGetUBound(getData, 1, hBound);

  // Вытаскиваем
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(getData, i, d);
    ShowMessage(d.ToString());
  end;

  // Очищаем PSafeArray
  SafeArrayDestroy(getData);
end;

// Отправка PSafeArray в DLL (данные не меняются)
procedure TForm1.Button3Click(Sender: TObject);
var
  i: Integer;
  d: Double;

  dataLength: Integer;
  matrix: Variant;

  psaBeginMatrix: PSafeArray;
  lBound, hBound: Integer;

  TestArray: TArrayDLL;
begin
  // Создаём массив Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // Заполняем массив
  for i := 0 to dataLength-1 do
    matrix[i] := i;

  // Преобразуем массив в PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // Отправляем в DLL
  TestArray := TArrayDLL.Create(nil);
  testArray.SetDoubleArray(psaBeginMatrix);
  TestArray.Free;

  // Вытаскиваем нижнию и верхнию границу
  SafeArrayGetLBound(psaBeginMatrix, 1, lBound);
  SafeArrayGetUBound(psaBeginMatrix, 1, hBound);

  // Вытаскиваем
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(psaBeginMatrix, i, d);
    ShowMessage(d.ToString());
  end;

  // Очищаем матрицу
  VarClear(matrix);
  
  // Очищаем PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
end;

// Отправляем и вытаскиваем
procedure TForm1.Button4Click(Sender: TObject);
var
  i: Integer;
  d: Double;

  dataLength: Integer;
  matrix: Variant;

  psaBeginMatrix: PSafeArray;
  lBound, hBound: Integer;

  TestArray: TArrayDLL;
  getData: PSafeArray;
begin
  // Создаём массив Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // Заполняем массив
  for i := 0 to dataLength-1 do
    matrix[i] := i;

  // Преобразуем массив в PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // Отправляем в DLL (значение увеливаются в 4 раза)
  TestArray := TArrayDLL.Create(nil);
  getData := testArray.SetAndGetDoubleArray(psaBeginMatrix);
  TestArray.Free;

  // Вытаскиваем нижнию и верхнию границу
  SafeArrayGetLBound(getData, 1, lBound);
  SafeArrayGetUBound(getData, 1, hBound);

  // Вытаскиваем
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(getData, i, d);
    ShowMessage(d.ToString());
  end;

  // Очищаем матрицу
  VarClear(matrix);
  
  // Очищаем PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
  SafeArrayDestroy(getData);
end;

// Отправка массива REF (работает)
procedure TForm1.Button5Click(Sender: TObject);
var
  i: Integer;
  d: Double;

  dataLength: Integer;
  matrix: Variant;

  psaBeginMatrix: PSafeArray;
  lBound, hBound: Integer;

  TestArray: TArrayDLL;
begin
  // Создаём массив Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // Заполняем массив
  for i := 0 to dataLength-1 do
    matrix[i] := i;

  // Преобразуем массив в PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // Отправляем в DLL (значение увеливаются в 4 раза)
  TestArray := TArrayDLL.Create(nil);
  testArray.SetRefDoubleArray(psaBeginMatrix);
  TestArray.Free;

  // Вытаскиваем нижнию и верхнию границу
  SafeArrayGetLBound(psaBeginMatrix, 1, lBound);
  SafeArrayGetUBound(psaBeginMatrix, 1, hBound);

  // Вытаскиваем
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(psaBeginMatrix, i, d);
    ShowMessage(d.ToString());
  end;

  // Очищаем матрицу
  VarClear(matrix);
  
  // Очищаем PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
end;

end.
