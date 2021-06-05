unit Unit2;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms, Vcl.Dialogs, Vcl.StdCtrls;

type
  TForm2 = class(TForm)
    Label1: TLabel;
    Edit1: TEdit;
    Label2: TLabel;
    Edit2: TEdit;
    CheckBox1: TCheckBox;
    Label3: TLabel;
    Edit3: TEdit;
    btnSave: TButton;
    procedure btnSaveClick(Sender: TObject);
  private
    { Private declarations }
  public
    isExit: Boolean;
  end;

var
  Form2: TForm2;

implementation

{$R *.dfm}

// Запустить
procedure TForm2.btnSaveClick(Sender: TObject);
begin
  isExit := True;
  Close();
end;

end.
