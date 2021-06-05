unit Unit1;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms, Vcl.Dialogs, Vcl.StdCtrls, Vcl.Grids,
  VclTee.TeeGDIPlus, VCLTee.TeEngine, Vcl.ExtCtrls, VCLTee.TeeProcs,
  VCLTee.Chart, ActiveX, Unit2, Generics.Collections;

type
  TForm1 = class(TForm)
    btnOpenFile: TButton;
    StringGrid1: TStringGrid;
    btnCreateChart: TButton;
    Image1: TImage;
    btnStartStringSSA: TButton;
    btnStartSSAK: TButton;
    btnStartSSA: TButton;
    procedure btnOpenFileClick(Sender: TObject);
    procedure btnCreateChartClick(Sender: TObject);
    procedure btnStartStringSSAClick(Sender: TObject);
    procedure btnStartSSAKClick(Sender: TObject);
    procedure SaveData();
    procedure LoadData();
    procedure RefreshChart();
    procedure btnStartSSAClick(Sender: TObject);
  private
    data: TList<Double>;
    result: TList<Double>;
  public
    { Public declarations }
  end;

var
  Form1: TForm1;
  Form2: TForm2;

implementation
uses
  DllSSA_TLB, ShellApi;
{$R *.dfm}

// �������� �����
procedure TForm1.btnOpenFileClick(Sender: TObject);
const
  separator: Char = ',';
var
  FromF: TextFile;
  s1, s2: String;
  i, j: Integer;
  OpenDialog1: TOpenDialog;
begin
  OpenDialog1 := TOpenDialog.Create(nil);
  if OpenDialog1.Execute then
  begin
    data := Generics.Collections.TList<Double>.Create;
    i := 0;
    AssignFile(FromF, OpenDialog1.FileName);
    Reset(FromF);

    while not eof(FromF) do
    begin
      readln(FromF, s1);
      i := i + 1;
      j := 0;
      while pos(separator, s1) <> 0 do
      begin
        s2 := copy(s1, 1, pos(separator, s1)-1);
        j := j + 1;
        delete(s1, 1, pos(separator, S1));
        StringGrid1.Cells[j-1, i-1] := s2;
      end;
      if pos(separator, s1)=0 then
      begin
        j := j + 1;
        StringGrid1.Cells[j-1, i-1] := s1;
        data.Add(s1.Replace('.',',').ToDouble());
      end;
      StringGrid1.ColCount := j;
      StringGrid1.RowCount := i+1;
    end;
    CloseFile(FromF);
  end;
end;

// ���������� �������
procedure TForm1.btnCreateChartClick(Sender: TObject);
begin
  RefreshChart();
end;

procedure TForm1.RefreshChart();
const
  x0 = 10;
var
  y0: Integer;  // ������ �������
  x, y, step, mash: Double;
  i: Integer;
begin
  // ������� �������
  Image1.Canvas.Pen.Color := clWhite;
  Image1.Canvas.Rectangle(0,0,Image1.Width,Image1.Height);
  // ������� ������ ����
  y0 := Image1.Height - 10;
  // ������ ���
  with Image1.Canvas do
  begin
    Pen.Color := clBlack;
    Pen.Width := 2;
    MoveTo(x0, 0);
    LineTo(x0, Image1.Height);
    MoveTo(0, y0);
    LineTo(Image1.Width, y0);
  end;

  // ��������� ��� �������
  step := Image1.Width / data.Count;
  if result <> nil then
    if data.Count < result.Count then
      step := Image1.Width / result.Count;

  // ��������� ������� �������
  mash := 1;
  for i := 0 to data.Count - 1 do
    if data[i] > mash then
      mash := data[i];
  if result <> nil then
    for i := 0 to result.Count - 1 do
      if result[i] > mash then
        mash := result[i];
  mash := Image1.Height / mash;

  // ������ ������ ���������
  Image1.Canvas.Pen.Color := clRed;
  x := x0 + step;
  y := y0 - data[0] * mash + 20;
  Image1.Canvas.MoveTo(Trunc(x), Trunc(y));

  for i := 0 to data.Count - 1 do
  begin
    y := data[i];
    y := y0 - y * mash + 20;
    Image1.Canvas.LineTo(Trunc(x), Trunc(y));
    x := x + step;
  end;

  // ������ ������ ���������
  if result <> nil then
  begin
    Image1.Canvas.Pen.Color := clGreen;
    x := x0 + step;
    y := y0 - result[0] * mash + 20;
    Image1.Canvas.MoveTo(Trunc(x), Trunc(y));

    for i := 0 to result.Count - 1 do
    begin
      y := result[i];
      y := y0 - y * mash + 20;
      Image1.Canvas.LineTo(Trunc(x), Trunc(y));
      x := x + step;
    end;
  end;
end;

// ��������� �������� (����� PSafeArray)
procedure TForm1.btnStartSSAClick(Sender: TObject);
var
  ssa: TMySSA;

  matrix: Variant;
  i: Integer;
  psaBeginMatrix, psaOutMatrix: PSafeArray;
  lBound, hBound: Integer;
  d: Double;

  l, countFirst, countForecasting: Integer;
  lyambdaPercent: Double;
  strL, strFirst, strLyambda, strForecasting: String;
begin
  // ����� ��� �������� SSA
  Form2 := TForm2.Create(nil);
  Form2.ShowModal();

  if Form2.isExit then
  begin
    // �������� ���������
    strL := Form2.Edit1.Text;
    if Form2.CheckBox1.Checked then
      begin
        strFirst := '0';
        strLyambda := Form2.Edit2.Text;
      end
    else
      begin
        strFirst := Form2.Edit2.Text;
        strLyambda := '0';
      end;
    strForecasting := Form2.Edit3.Text;

    // ����� ��������� ��� ������� ���������
    l := strL.ToInteger();
    lyambdaPercent := strLyambda.ToDouble();
    countFirst := strFirst.ToInteger();
    countForecasting := strForecasting.ToInteger();

    // ������ �������
    matrix := VarArrayCreate([0, data.Count - 1], varDouble);

    // ��������� �������
    for i := 0 to data.Count - 1 do
      matrix[i] := data[i];

    // ����������� ������ � PSafeArray
    psaBeginMatrix := PSafeArray(TVarData(matrix).VArray);

    // ���������� � DLL � ���������� � ��������
    ssa := TMySSA.Create(nil);
    ssa.SetBeginData(psaBeginMatrix, l);
    ssa.StartAutoSSA(psaOutMatrix, lyambdaPercent, countFirst, countForecasting);
    ssa.Free;

    // ����������� ������ � ������� �������
    SafeArrayGetLBound(psaOutMatrix, 1, lBound);
    SafeArrayGetUBound(psaOutMatrix, 1, hBound);

    // �����������
    result := Generics.Collections.TList<Double>.Create;
    for i := lBound to hBound do
    begin
      SafeArrayGetElement(psaOutMatrix, i, d);
      result.Add(d);
    end;

    // ������� �������
    VarClear(matrix);

    // ������� PSafeArray
    // TODO: ���������� ���������� ������, �� ����� ���� ���������� ��� ������ �� ������!
    // TODO: ���������� ������ ������
    //SafeArrayDestroyDescriptor(psaBeginMatrix);
    SafeArrayDestroyData(psaBeginMatrix);
    //SafeArrayDestroy(psaBeginMatrix);
    SafeArrayDestroy(psaOutMatrix);

    // ������������� ������
    RefreshChart();
  end;


end;

// ��������� �������� ����� ������
procedure TForm1.btnStartStringSSAClick(Sender: TObject);
var
  ssa: TMySSA;
  i: Integer;
  l, countFirst, countForecasting: Integer;
  lyambdaPercent: Double;

  strL, strFirst, strLyambda, strForecasting: String;

  beginData: TStringBuilder;
  resultData: String;
  prResultData: TList<Double>;
  prValueStr: TStringBuilder;
begin
  // ����� ��� �������� SSA
  Form2 := TForm2.Create(nil);
  Form2.ShowModal();

  if Form2.isExit then
  begin
    // �������� ���������
    strL := Form2.Edit1.Text;
    if Form2.CheckBox1.Checked then
      begin
        strFirst := '0';
        strLyambda := Form2.Edit2.Text;
      end
    else
      begin
        strFirst := Form2.Edit2.Text;
        strLyambda := '0';
      end;
    strForecasting := Form2.Edit3.Text;

    // ����� ��������� ��� ������� ���������
    l := strL.ToInteger();
    lyambdaPercent := strLyambda.ToDouble();
    countFirst := strFirst.ToInteger();
    countForecasting := strForecasting.ToInteger();

    // ������ ������ ��������
    ssa := TMySSA.Create(nil);

    // ����������� ������ � ������
    beginData := TStringBuilder.Create();
    for i := 0 to data.Count - 1 do
    begin
      beginData.Append(data[i].ToString());
      beginData.Append(';');
    end;

    // ���������
    resultData := ssa.StartAutoStringSSA(beginData.ToString(), l, lyambdaPercent, countFirst, countForecasting);

    // ����������� ������ ������� � ������
    result := Generics.Collections.TList<Double>.Create;
    prValueStr := TStringBuilder.Create();
    for i := 1 to resultData.Length do
    begin
      if resultData[i] <> ';' then
        prValueStr.Append(resultData[i])
      else
      begin
        result.Add(prValueStr.ToString().ToDouble());
        prValueStr.Clear;
      end;
    end;

    // �����������
    ssa.Free;
    beginData.Free;
    prValueStr.Free;

    // ������
    RefreshChart();
  end;
end;

// ����� ��������� ����� �������!!!
// TODO: ����������� �������� ���� � ������ ��� ���������� � ��������,
// ����� ������������ ���� ����� �������
// P.S. ����� ����� ��������� ������ ����� �����������
procedure TForm1.btnStartSSAKClick(Sender: TObject);
var
  ShExecInfo: TShellExecuteInfo;
  x0, y0: Integer;
  x, y, step: Double;
  i: Integer;
  strL, strFirst, strLyambda, strForecasting, fullParam: String;
begin
  // ����� ��� �������� SSA
  Form2 := TForm2.Create(nil);
  Form2.ShowModal();

  if Form2.isExit then
  begin
    // �������� ���������
    strL := Form2.Edit1.Text;
    if Form2.CheckBox1.Checked then
      begin
        strFirst := '0';
        strLyambda := Form2.Edit2.Text;
      end
    else
      begin
        strFirst := Form2.Edit2.Text;
        strLyambda := '0';
      end;
    strForecasting := Form2.Edit3.Text;

    fullParam := 'ConsoleSSA\data.csv ' + strL + ' ' + strLyambda + ' ' + strFirst + ' ' + strForecasting;

    // ��������� � data.csv ������� ������
    SaveData();
  
    // ��������� ConsoleSSA
    FillChar(ShExecInfo, sizeof(ShExecInfo), 0);
    with ShExecInfo do
    begin
      cbSize := sizeof(ShExecInfo);
      fMask := SEE_MASK_NOCLOSEPROCESS;
      lpFile := PChar('ConsoleSSA\ConsoleSSA.exe');
      lpParameters := PChar(fullParam);
      lpVerb := 'open';
      nShow := SW_SHOW;
    end;
    if (ShellExecuteEx(@ShExecInfo) and (ShExecInfo.hProcess <> 0)) then
      try
        WaitForSingleObject(ShExecInfo.hProcess, INFINITE);
      finally
        CloseHandle(ShExecInfo.hProcess);
      end;

    // ��������� �� result.csv ����� ������
    LoadData();

    // ������ ������ ���������
    RefreshChart();
  end;
end;

// ������������� ���������� ��������� ������
procedure TForm1.SaveData();
var
  Writer: TStreamWriter;
  i: Integer;
begin
  Writer := TStreamWriter.Create('ConsoleSSA/data.csv',
    false, TEncoding.UTF8);

  for i := 0 to data.Count - 1 do
    begin
      Writer.WriteLine(StringGrid1.Cells[1, i]);
    end;

  Writer.Free();
end;

// ������������� �������� �����������
procedure TForm1.LoadData;
var
  Reader: TStreamReader;
  i: Integer;
  str: String;
begin
  Reader := TStreamReader.Create('ConsoleSSA/result.csv', TEncoding.UTF8);

  StringGrid1.ColCount := StringGrid1.ColCount + 1;

  result := Generics.Collections.TList<Double>.Create;

  i := 0;
  while Reader.Peek() >= 0 do
  begin
    str := Reader.ReadLine();
    if i > StringGrid1.RowCount then
      StringGrid1.RowCount := StringGrid1.RowCount + 1;
    StringGrid1.Cells[StringGrid1.ColCount - 1,i] := str;
    result.Add(str.Replace('.',',').ToDouble());
    i := i + 1;
  end;

  Reader.Free();
end;

end.
