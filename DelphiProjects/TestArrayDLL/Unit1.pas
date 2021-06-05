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

// �������� PSafeArray � ����� �� �����
procedure TForm1.Button2Click(Sender: TObject);
var
  i: Integer;
  d: Double;

  dataLength: Integer;
  matrix: Variant;

  psaBeginMatrix: PSafeArray;
  lBound, hBound: Integer;
begin
  // ������ ������ Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // ��������� ������
  for i := 0 to dataLength-1 do
    matrix[i] := i * 3;

  // ����������� ������ � PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // ����������� ������ � ������� �������
  SafeArrayGetLBound(psaBeginMatrix, 1, lBound);
  SafeArrayGetUBound(psaBeginMatrix, 1, hBound);

  // �����������
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(psaBeginMatrix, i, d);
    ShowMessage(d.ToString());
  end;

  // ������� �������
  VarClear(matrix);

  // ������� PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
end;

// ��������� PSafeArray �� DLL
procedure TForm1.Button1Click(Sender: TObject);
var
  TestArray: TArrayDLL;
  getData: PSafeArray;

  i, lBound, hBound: Integer;
  d: Integer;
begin
  // ������ ������ ��� ������ DLL
  TestArray := TArrayDLL.Create(nil);

  // ����������� PSafeArray �� DLL [4;3;7]
  getData := testArray.GetIntArray();

  // ����������� ������
  TestArray.Free;

  // ����������� ������ � ������� �������
  SafeArrayGetLBound(getData, 1, lBound);
  SafeArrayGetUBound(getData, 1, hBound);

  // �����������
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(getData, i, d);
    ShowMessage(d.ToString());
  end;

  // ������� PSafeArray
  SafeArrayDestroy(getData);
end;

// �������� PSafeArray � DLL (������ �� ��������)
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
  // ������ ������ Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // ��������� ������
  for i := 0 to dataLength-1 do
    matrix[i] := i;

  // ����������� ������ � PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // ���������� � DLL
  TestArray := TArrayDLL.Create(nil);
  testArray.SetDoubleArray(psaBeginMatrix);
  TestArray.Free;

  // ����������� ������ � ������� �������
  SafeArrayGetLBound(psaBeginMatrix, 1, lBound);
  SafeArrayGetUBound(psaBeginMatrix, 1, hBound);

  // �����������
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(psaBeginMatrix, i, d);
    ShowMessage(d.ToString());
  end;

  // ������� �������
  VarClear(matrix);
  
  // ������� PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
end;

// ���������� � �����������
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
  // ������ ������ Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // ��������� ������
  for i := 0 to dataLength-1 do
    matrix[i] := i;

  // ����������� ������ � PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // ���������� � DLL (�������� ����������� � 4 ����)
  TestArray := TArrayDLL.Create(nil);
  getData := testArray.SetAndGetDoubleArray(psaBeginMatrix);
  TestArray.Free;

  // ����������� ������ � ������� �������
  SafeArrayGetLBound(getData, 1, lBound);
  SafeArrayGetUBound(getData, 1, hBound);

  // �����������
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(getData, i, d);
    ShowMessage(d.ToString());
  end;

  // ������� �������
  VarClear(matrix);
  
  // ������� PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
  SafeArrayDestroy(getData);
end;

// �������� ������� REF (��������)
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
  // ������ ������ Double
  dataLength := 8;
  matrix := VarArrayCreate([0, dataLength - 1], varDouble);

  // ��������� ������
  for i := 0 to dataLength-1 do
    matrix[i] := i;

  // ����������� ������ � PSafeArray
  psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

  // ���������� � DLL (�������� ����������� � 4 ����)
  TestArray := TArrayDLL.Create(nil);
  testArray.SetRefDoubleArray(psaBeginMatrix);
  TestArray.Free;

  // ����������� ������ � ������� �������
  SafeArrayGetLBound(psaBeginMatrix, 1, lBound);
  SafeArrayGetUBound(psaBeginMatrix, 1, hBound);

  // �����������
  for i := lBound to hBound do
  begin
    SafeArrayGetElement(psaBeginMatrix, i, d);
    ShowMessage(d.ToString());
  end;

  // ������� �������
  VarClear(matrix);
  
  // ������� PSafeArray
  SafeArrayDestroy(psaBeginMatrix);
end;

end.
