unit Unit1;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms, Vcl.Dialogs, Vcl.StdCtrls;

type
  TForm1 = class(TForm)
    txtResult: TEdit;
    btnBackspace: TButton;
    btnDelete: TButton;
    btnSwap: TButton;
    btnDiv: TButton;
    btnSeven: TButton;
    btnEight: TButton;
    btnNine: TButton;
    btnFour: TButton;
    btnFive: TButton;
    btnSix: TButton;
    btnOne: TButton;
    btnTwo: TButton;
    btnThree: TButton;
    btnZero: TButton;
    btnDote: TButton;
    btnMult: TButton;
    btnMinus: TButton;
    btnPlus: TButton;
    btnEqual: TButton;
    procedure btnNumberClick(Sender : TObject);
    procedure btnBackspaceClick(Sender: TObject);
    procedure btnDeleteClick(Sender: TObject);
    procedure btnSwapClick(Sender: TObject);
    procedure btnCharClick(Sender: TObject);
    procedure btnEqualClick(Sender: TObject);
    procedure btnDoteClick(Sender: TObject);
  private
    { Private declarations }
    prTxtResult: String;
    firstNumber: Double;
    secondNumber: Double;
    result: Double;
    prNumber: Double;
    action: String;
    isStart: Boolean;
    procedure ClearData(ClearAll: Boolean);
  public
    { Public declarations }
  end;

var
  Form1: TForm1;

implementation
uses TestDLL_TLB;

{$R *.dfm}

// Стереть последний символ
procedure TForm1.btnBackspaceClick(Sender: TObject);
begin
  prTxtResult := txtResult.Text;
  if(prTxtResult.Length <> 1) then
    begin
      txtResult.Text := prTxtResult.Remove(prTxtResult.Length - 1);
    end
  else
    txtResult.Text := '0';
end;

// Стереть всё
procedure TForm1.btnDeleteClick(Sender: TObject);
begin
  ClearData(true);
end;

procedure TForm1.ClearData(ClearAll: Boolean);
begin
  if(ClearAll) then
    txtResult.Text := '0';
  firstNumber := 0;
  secondNumber := 0;
  isStart := False;
end;

// Смена знака (+-)
procedure TForm1.btnSwapClick(Sender: TObject);
begin
  prTxtResult := txtResult.Text;
  prNumber := prTxtResult.ToDouble();
  prNumber := prNumber * -1;
  txtResult.Text := prNumber.ToString();
end;

// Кнопки цифр
procedure TForm1.btnNumberClick(Sender: TObject);
begin
  if(txtResult.Text = '0') then
    txtResult.Text := (Sender as TButton).Caption
  else
    begin
      txtResult.Text := (txtResult.Text + (Sender as TButton).Caption);
    end;
end;

// Кнопки знаков
procedure TForm1.btnCharClick(Sender: TObject);
begin
  prTxtResult := txtResult.Text;
  firstNumber := prTxtResult.ToDouble();
  action := (Sender as TButton).Caption;
  txtResult.Text := '0';
  isStart := True;
end;

// Кнопка запятой
procedure TForm1.btnDoteClick(Sender: TObject);
begin
  prTxtResult := txtResult.Text;
  if(prTxtResult.Contains(',') <> True) then
    txtResult.Text := prTxtResult + ',';
end;

// Кнопка равенства
procedure TForm1.btnEqualClick(Sender: TObject);
var
Calc: TCalc;

begin
  if(isStart) then
    begin
      prTxtResult := txtResult.Text;
      secondNumber := prTxtResult.ToDouble();
      if(action = '/') then
        if(secondNumber <> 0) then
          result := firstNumber / secondNumber
        else
          MessageDlg('Внимание! Деление на ноль!', mtInformation, [mbOk], 0, mbOk);
      if(action = '*') then
        result := firstNumber * secondNumber;
      if(action = '-') then
        result := firstNumber - secondNumber;
      if(action = '+') then
      begin
        Calc := TCalc.Create(nil);
        try
          result := Calc.Summ(firstNumber,secondNumber);
        finally
          Calc.Free;
        end;
      end;
      txtResult.Text := result.ToString();
      ClearData(false);
    end;
end;

end.
